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
        Schema::create('site_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('ip_address')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable(); // Chrome, Safari, Firefox, Edge, etc.
            $table->string('platform')->nullable(); // Windows, Android, iOS, MacOS, Linux
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('city')->nullable();
            $table->string('landing_page')->nullable();
            $table->string('referrer')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->integer('page_views_count')->default(1);
            $table->timestamp('first_visited_at')->nullable();
            $table->timestamp('last_visited_at')->nullable();
            $table->timestamps();
        });

        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_visitor_id')->nullable()->constrained('site_visitors')->cascadeOnDelete();
            $table->string('session_id')->index();
            $table->string('url');
            $table->string('route_name')->nullable();
            $table->string('page_title')->nullable();
            $table->string('referrer')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamp('viewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('site_visitors');
    }
};
