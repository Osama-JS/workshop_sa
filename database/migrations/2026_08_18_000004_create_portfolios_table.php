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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('client_name')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('location')->nullable();
            $table->string('main_image')->nullable();
            $table->string('video_url')->nullable(); // Youtube/Vimeo or video path
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained('portfolios')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('media_type')->default('image'); // image, video, pdf
            $table->string('file_size')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_attachments');
        Schema::dropIfExists('portfolios');
    }
};
