<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        return view('frontend.order', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_whatsapp' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'service_id' => ['nullable', 'exists:services,id'],
            'wood_type' => ['nullable', 'string', 'max:100'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'budget_range' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:10', 'max:10000'],
            'attachments.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,webp,pdf,dwg,zip', 'max:10240'],
        ]);

        // Generate unique order number (e.g. ORD-2026-XXXX)
        do {
            $orderNumber = 'ORD-' . date('Y') . '-' . strtoupper(Str::random(5));
        } while (CustomOrder::where('order_number', $orderNumber)->exists());

        // Handle uploaded attachment files (images, blueprints, PDFs)
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('orders/attachments', 'public');
                $attachments[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => round($file->getSize() / 1024) . ' KB',
                    'type' => $file->getClientOriginalExtension(),
                ];
            }
        }

        $order = CustomOrder::create([
            'order_number' => $orderNumber,
            'service_id' => $validated['service_id'] ?? null,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_whatsapp' => $validated['customer_whatsapp'] ?? $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'wood_type' => $validated['wood_type'] ?? null,
            'dimensions' => $validated['dimensions'] ?? null,
            'budget_range' => $validated['budget_range'] ?? null,
            'description' => $validated['description'],
            'attachments' => $attachments,
            'status' => 'pending',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order_number' => $orderNumber,
                'message' => app()->getLocale() === 'ar'
                    ? "تم استلام طلبكم بنجاح! رقم تتبع طلبك هو: {$orderNumber}. سيتواصل معكم فريقنا الهندسي قريباً."
                    : "Your order has been placed successfully! Tracking Code: {$orderNumber}. Our engineering team will contact you soon."
            ]);
        }

        return redirect()->route('order.track', $orderNumber)->with('success', app()->getLocale() === 'ar'
            ? "تم إرسال طلبكم بنجاح! رقم المرجع هو: {$orderNumber}"
            : "Your request was submitted! Tracking number: {$orderNumber}");
    }

    public function track($tracking_code = null, Request $request)
    {
        $code = $tracking_code ?: $request->get('code');
        $order = null;
        if ($code) {
            $order = CustomOrder::where('order_number', trim($code))->with('service')->first();
        }

        return view('frontend.order_track', compact('order', 'code'));
    }
}
