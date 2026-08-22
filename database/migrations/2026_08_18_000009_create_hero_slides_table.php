<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('subtitle_ar')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('btn_text_ar')->nullable();
            $table->string('btn_text_en')->nullable();
            $table->string('btn_url')->nullable();
            $table->string('secondary_btn_text_ar')->nullable();
            $table->string('secondary_btn_text_en')->nullable();
            $table->string('secondary_btn_url')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
