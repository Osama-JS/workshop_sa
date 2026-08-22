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
        // 1. Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Slug/identifier: e.g. super_admin, editor, customer_service
            $table->string('name_ar'); // Display name in Arabic
            $table->string('name_en'); // Display name in English
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_system')->default(false); // Cannot be deleted if system role (e.g. super_admin)
            $table->timestamps();
        });

        // 2. Permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. services.view, services.create, settings.edit
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('group'); // e.g. services, portfolio, settings, users, orders, messages, analytics
            $table->string('group_ar');
            $table->string('group_en');
            $table->timestamps();
        });

        // 3. Role has permissions pivot
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        // 4. User has roles pivot
        Schema::create('user_has_roles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->primary(['user_id', 'role_id']);
        });

        // Add phone, avatar, is_active, last_login_at to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'is_active', 'last_login_at', 'last_login_ip']);
        });
        Schema::dropIfExists('user_has_roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
