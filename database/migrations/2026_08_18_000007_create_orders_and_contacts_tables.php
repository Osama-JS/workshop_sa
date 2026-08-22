<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Custom Woodwork Orders
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_whatsapp')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('wood_type')->nullable(); // e.g. Oak, Walnut, MDF, Teak, Beach
            $table->string('dimensions')->nullable(); // Length x Width x Height or approximate sizes
            $table->string('budget_range')->nullable();
            $table->text('description');
            $table->json('attachments')->nullable(); // array of uploaded sample/plan image paths
            $table->enum('status', ['pending', 'in_review', 'contacted', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
        });

        // Contact Messages
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->text('reply_notes')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('custom_orders');
    }
};
