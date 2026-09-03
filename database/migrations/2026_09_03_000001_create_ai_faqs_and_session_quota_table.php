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
        // 1. AI Q&A Knowledge Base
        Schema::create('ai_faqs', function (Blueprint $table) {
            $table->id();
            $table->text('question_ar');
            $table->text('question_en')->nullable();
            $table->longText('answer_ar');
            $table->longText('answer_en')->nullable();
            $table->string('category')->default('general'); // general, services, bedrooms, offices, booths, materials, pricing, warranty, orders
            $table->string('keywords')->nullable(); // comma-separated search tokens
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Add Quota & Token usage tracking columns to ai_chat_sessions
        Schema::table('ai_chat_sessions', function (Blueprint $table) {
            $table->integer('tokens_used_today')->default(0)->after('total_messages');
            $table->integer('messages_count_today')->default(0)->after('tokens_used_today');
            $table->date('last_activity_date')->nullable()->after('messages_count_today');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_chat_sessions', function (Blueprint $table) {
            $table->dropColumn(['tokens_used_today', 'messages_count_today', 'last_activity_date']);
        });

        Schema::dropIfExists('ai_faqs');
    }
};
