<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\CustomOrder;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrdersAndContactTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_public_contact_form_submission(): void
    {
        $res = $this->post(route('contact.send'), [
            'name' => 'سلطان القحطاني',
            'email' => 'sultan@example.com',
            'phone' => '0501234567',
            'subject' => 'استفسار عن أسعار بوثات المعارض',
            'message' => 'السلام عليكم، نود الاستفسار عن إمكانية تنفيذ وتفصيل بوث معرض بمساحة 6x4 متر.',
        ]);

        $res->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'sultan@example.com',
            'name' => 'سلطان القحطاني',
            'is_read' => false,
        ]);
    }

    public function test_public_custom_order_submission_with_attachments(): void
    {
        Storage::fake('public');
        $service = Service::first();

        $res = $this->post(route('order.store'), [
            'customer_name' => 'محمد السبيعي',
            'customer_phone' => '0555555555',
            'customer_whatsapp' => '0555555555',
            'customer_email' => 'mohammed@example.com',
            'service_id' => $service->id,
            'wood_type' => 'خشب جوز أمريكي (Walnut)',
            'dimensions' => 'غرفة 5 × 4 متر',
            'budget_range' => '25,000 - 50,000 ريال',
            'description' => 'نريد تفصيل غرفة نوم ماستر مع تكسيات خشبية وخزائن ملابس مدمجة بإضاءات مخفية.',
            'attachments' => [
                UploadedFile::fake()->image('blueprint.jpg'),
                UploadedFile::fake()->create('specifications.pdf', 100, 'application/pdf'),
            ],
        ]);

        $this->assertDatabaseHas('custom_orders', [
            'customer_name' => 'محمد السبيعي',
            'status' => 'pending',
            'wood_type' => 'خشب جوز أمريكي (Walnut)',
        ]);

        $order = CustomOrder::where('customer_name', 'محمد السبيعي')->first();
        $this->assertNotNull($order->order_number);
        $this->assertCount(2, $order->attachments);
    }

    public function test_order_tracking_page(): void
    {
        $order = CustomOrder::create([
            'order_number' => 'ORD-2026-TEST1',
            'customer_name' => 'خالد العتيبي',
            'customer_phone' => '0509999999',
            'description' => 'مكتب تنفيذي مخصص',
            'status' => 'in_progress',
        ]);

        // 1. Successful tracking
        $res = $this->withSession(['locale' => 'ar'])->get(route('order.track', $order->order_number));
        $res->assertStatus(200);
        $res->assertSee('ORD-2026-TEST1');
        $res->assertSee('قيد التصنيع في الورشة');

        // 2. Invalid tracking code
        $res = $this->withSession(['locale' => 'ar'])->get(route('order.track', 'ORD-INVALID-9999'));
        $res->assertStatus(200);
        $res->assertSee('لم يتم العثور على طلب بهذا الرقم');
    }

    public function test_admin_custom_orders_management(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $order = CustomOrder::create([
            'order_number' => 'ORD-2026-ADM01',
            'customer_name' => 'فهد الدوسري',
            'customer_phone' => '0544444444',
            'description' => 'باب رئيسي خشب تيك فاخر',
            'status' => 'pending',
        ]);

        // 1. Orders Index
        $res = $this->actingAs($superAdmin)->get(route('admin.orders.index'));
        $res->assertStatus(200);
        $res->assertSee('ORD-2026-ADM01');

        // 2. Order Show
        $res = $this->actingAs($superAdmin)->get(route('admin.orders.show', $order->id));
        $res->assertStatus(200);
        $res->assertSee('فهد الدوسري');

        // 3. Update Status
        $res = $this->actingAs($superAdmin)->put(route('admin.orders.status.update', $order->id), [
            'status' => 'in_review',
            'admin_notes' => 'تم التواصل مع العميل وإرسال عرض السعر المبدئي.',
        ]);
        $res->assertSessionHas('success');
        $this->assertDatabaseHas('custom_orders', [
            'id' => $order->id,
            'status' => 'in_review',
            'admin_notes' => 'تم التواصل مع العميل وإرسال عرض السعر المبدئي.',
        ]);

        // 4. Delete Order
        $res = $this->actingAs($superAdmin)->delete(route('admin.orders.destroy', $order->id));
        $res->assertRedirect(route('admin.orders.index'));
        $this->assertDatabaseMissing('custom_orders', ['id' => $order->id]);
    }

    public function test_admin_contact_messages_management(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $msg = ContactMessage::create([
            'name' => 'طارق الزهراني',
            'email' => 'tariq@example.com',
            'phone' => '0567890123',
            'subject' => 'استفسار عن الشحن والتوصيل',
            'message' => 'هل تقومون بالتوصيل والتركيب في كافة مدن المملكة؟',
            'is_read' => false,
        ]);

        // 1. Index
        $res = $this->actingAs($superAdmin)->get(route('admin.messages.index'));
        $res->assertStatus(200);
        $res->assertSee('طارق الزهراني');

        // 2. Show (auto marks read)
        $res = $this->actingAs($superAdmin)->get(route('admin.messages.show', $msg->id));
        $res->assertStatus(200);
        $this->assertDatabaseHas('contact_messages', [
            'id' => $msg->id,
            'is_read' => true,
        ]);

        // 3. Save Reply Notes
        $res = $this->actingAs($superAdmin)->put(route('admin.messages.reply', $msg->id), [
            'reply_notes' => 'تم الاتصال بالعميل وإبلاغه بتوفر الشحن والتركيب لكافة المدن.',
        ]);
        $res->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'id' => $msg->id,
            'reply_notes' => 'تم الاتصال بالعميل وإبلاغه بتوفر الشحن والتركيب لكافة المدن.',
        ]);

        // 4. Delete Message
        $res = $this->actingAs($superAdmin)->delete(route('admin.messages.destroy', $msg->id));
        $res->assertRedirect(route('admin.messages.index'));
        $this->assertDatabaseMissing('contact_messages', ['id' => $msg->id]);
    }

    public function test_realtime_notifications_check_endpoint(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $res = $this->actingAs($superAdmin)->getJson(route('admin.notifications.check'));
        $res->assertStatus(200);
        $res->assertJsonStructure([
            'pending_orders_count',
            'unread_messages_count',
            'total_alerts',
            'timestamp',
        ]);
    }
}
