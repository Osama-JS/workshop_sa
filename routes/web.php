<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CustomOrderController;
use App\Http\Controllers\Admin\CustomPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LocaleController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Language Switch Route
Route::get('lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

// Public Frontend Routes
Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::get('/services', [\App\Http\Controllers\Frontend\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [\App\Http\Controllers\Frontend\ServiceController::class, 'show'])->name('services.show');
Route::get('/portfolio', [\App\Http\Controllers\Frontend\PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{slug}', [\App\Http\Controllers\Frontend\PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/about-us', [\App\Http\Controllers\Frontend\PageController::class, 'about'])->name('about');
Route::get('/page/{slug}', [\App\Http\Controllers\Frontend\PageController::class, 'show'])->name('page.show');

// Public Contact & Custom Orders (Throttled against spam)
Route::get('/contact', [\App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [\App\Http\Controllers\Frontend\ContactController::class, 'send'])->name('contact.send')->middleware('throttle:10,1');
Route::get('/custom-order', [\App\Http\Controllers\Frontend\OrderController::class, 'create'])->name('order.create');
Route::post('/custom-order', [\App\Http\Controllers\Frontend\OrderController::class, 'store'])->name('order.store')->middleware('throttle:10,1');
Route::get('/order-tracking/{code?}', [\App\Http\Controllers\Frontend\OrderController::class, 'track'])->name('order.track');

// Public AI Assistant Chat Endpoints
Route::get('/ai-chat/init', [\App\Http\Controllers\Frontend\AiChatController::class, 'init'])->name('ai.chat.init');
Route::post('/ai-chat/send', [\App\Http\Controllers\Frontend\AiChatController::class, 'send'])->name('ai.chat.send')->middleware('throttle:30,1');
Route::post('/ai-chat/order-idea', [\App\Http\Controllers\Frontend\AiChatController::class, 'orderFromIdea'])->name('ai.chat.order-idea')->middleware('throttle:10,1');
Route::post('/ai-chat/clear', [\App\Http\Controllers\Frontend\AiChatController::class, 'clear'])->name('ai.chat.clear');

// SEO Routes: Dynamic Sitemap & Robots.txt
Route::get('/sitemap.xml', [\App\Http\Controllers\Frontend\SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\Frontend\SeoController::class, 'robots'])->name('seo.robots');

// Admin Routes Group
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest Auth Routes
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated Admin Routes
    Route::middleware('auth')->group(function () {
        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard Home
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Profile Management
        Route::get('profile', [AuthController::class, 'showProfile'])->name('profile');
        Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');

        // Global Translation Endpoint
        Route::post('translate', [\App\Http\Controllers\Admin\TranslationController::class, 'translate'])->name('translate');

        // Real-Time Push Notification Checker
        Route::get('notifications/check', [\App\Http\Controllers\Admin\NotificationController::class, 'checkNew'])->name('notifications.check');

        // Dynamic RBAC: Roles Management
        Route::middleware('permission:roles.manage')->group(function () {
            Route::resource('roles', RoleController::class)->except(['show']);
        });

        // Dynamic RBAC: Admin Users Management
        Route::middleware('permission:users.manage')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
            Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });

        // Hero Slides & Banner
        Route::middleware('permission:hero.view')->group(function () {
            Route::resource('hero-slides', \App\Http\Controllers\Admin\HeroSlideController::class)->except(['show']);
        });

        // Services
        Route::middleware('permission:services.view')->group(function () {
            Route::resource('services', ServiceController::class)->except(['show']);
        });

        // Portfolio
        Route::middleware('permission:portfolios.view')->group(function () {
            Route::resource('portfolios', PortfolioController::class)->except(['show']);
            Route::delete('portfolios/attachments/{attachment}', [PortfolioController::class, 'deleteAttachment'])->name('portfolios.attachments.destroy');
        });

        // Custom Orders
        Route::middleware('permission:orders.view')->group(function () {
            Route::get('orders', [CustomOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [CustomOrderController::class, 'show'])->name('orders.show');
            Route::put('orders/{order}/status', [CustomOrderController::class, 'updateStatus'])->name('orders.status.update')->middleware('permission:orders.edit');
            Route::delete('orders/{order}', [CustomOrderController::class, 'destroy'])->name('orders.destroy')->middleware('permission:orders.delete');
        });

        // Contact Messages
        Route::middleware('permission:messages.view')->group(function () {
            Route::get('messages', [ContactMessageController::class, 'index'])->name('messages.index');
            Route::get('messages/{message}', [ContactMessageController::class, 'show'])->name('messages.show');
            Route::post('messages/{message}/toggle-read', [ContactMessageController::class, 'toggleRead'])->name('messages.toggle-read');
            Route::put('messages/{message}/reply', [ContactMessageController::class, 'saveReplyNotes'])->name('messages.reply');
            Route::delete('messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy')->middleware('permission:messages.delete');
        });

        // Custom Pages
        Route::middleware('permission:pages.view')->group(function () {
            Route::resource('pages', CustomPageController::class)->except(['show']);
        });

        // About Sections
        Route::middleware('permission:about.edit')->group(function () {
            Route::get('about', [AboutController::class, 'index'])->name('about.index');
            Route::put('about', [AboutController::class, 'update'])->name('about.update');
        });

        // Testimonials
        Route::middleware('permission:testimonials.manage')->group(function () {
            Route::resource('testimonials', TestimonialController::class)->except(['show']);
        });

        // Settings & SMTP
        Route::middleware('permission:settings.view')->group(function () {
            Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
            Route::put('settings', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:settings.edit');
            Route::post('settings/send-test-mail', [SettingController::class, 'sendTestMail'])->name('settings.send-test-mail')->middleware('permission:settings.edit');
        });

        // AI Assistant Knowledge Base & Chat Logs
        Route::resource('ai-ideas', \App\Http\Controllers\Admin\AiDesignIdeaController::class)->except(['show']);
        Route::resource('ai-logs', \App\Http\Controllers\Admin\AiChatLogController::class)->only(['index', 'show', 'destroy']);
    });
});

// Direct Public Storage File Serving Route (Guarantees no 403 Forbidden on Apache/XAMPP root mode)
Route::get('storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.local');

