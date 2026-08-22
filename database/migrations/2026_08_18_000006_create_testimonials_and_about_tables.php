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
        // Testimonials
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('client_name_ar');
            $table->string('client_name_en');
            $table->string('client_position_ar')->nullable();
            $table->string('client_position_en')->nullable();
            $table->string('company_ar')->nullable();
            $table->string('company_en')->nullable();
            $table->unsignedTinyInteger('rating')->default(5); // 1 to 5
            $table->text('comment_ar');
            $table->text('comment_en');
            $table->string('client_avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // About Sections
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // story, vision, mission, why_us, stats, values
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('subtitle_ar')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->longText('content_ar')->nullable();
            $table->longText('content_en')->nullable();
            $table->string('image')->nullable();
            $table->json('meta_data')->nullable(); // For dynamic counters (e.g. [{"number": 15, "label_ar": "سنة خبرة", "label_en": "Years Experience"}])
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_sections');
        Schema::dropIfExists('testimonials');
    }
};
