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
        // 1. AI Knowledge Base: Design Ideas & Pinterest Inspirations
        Schema::create('ai_design_ideas', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('category')->default('bedrooms'); // bedrooms, offices, tables, booths, wall_cladding, cabinets, decor, other
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('pinterest_url')->nullable();
            $table->string('image')->nullable();
            $table->string('wood_type')->nullable(); // e.g. خشب بلوط، خشب جوز، خشب زان
            $table->string('dimensions')->nullable(); // e.g. 5متر × 4متر
            $table->string('estimated_price_range')->nullable(); // e.g. 10,000 - 20,000 ريال
            $table->string('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. AI Chat Sessions
        Schema::create('ai_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_token')->unique();
            $table->string('visitor_ip')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('user_phone')->nullable();
            $table->foreignId('order_id')->nullable()->constrained('custom_orders')->nullOnDelete();
            $table->integer('total_messages')->default(0);
            $table->timestamps();
        });

        // 3. AI Chat Messages
        Schema::create('ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_chat_session_id')->constrained('ai_chat_sessions')->cascadeOnDelete();
            $table->string('sender'); // user, assistant, system
            $table->longText('message');
            $table->string('image_path')->nullable();
            $table->json('metadata')->nullable(); // suggested design IDs, action triggers, tracking codes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_chat_messages');
        Schema::dropIfExists('ai_chat_sessions');
        Schema::dropIfExists('ai_design_ideas');
    }
};
