<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PublicArticleController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\PublicGalleryController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [PublicEventController::class, 'show'])->name('events.show');
Route::get('/artikel', [PublicArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [PublicArticleController::class, 'show'])->name('articles.show');
Route::get('/galeri', [PublicGalleryController::class, 'index'])->name('galleries.index');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/{product}/preorder', [ProductController::class, 'storePreorder'])->name('products.preorder.store')->middleware('auth');

// Auth user publik (forum)
Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register']);
});

Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('profile.avatar');

    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/produk/{product}/pesan', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/produk/{product}/pesan', [OrderController::class, 'store'])->name('orders.store');

    Route::get('/testimoni/kirim', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimoni/kirim', [TestimonialController::class, 'store'])->name('testimonials.store');
});

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'is-admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Extra routes (before resource to avoid slug conflict)
        Route::patch('banners/{banner}/toggle', [AdminBannerController::class, 'toggle'])->name('banners.toggle');
        Route::patch('banners/reorder', [AdminBannerController::class, 'reorder'])->name('banners.reorder');
        Route::patch('events/{event}/toggle-featured', [AdminEventController::class, 'toggleFeatured'])->name('events.toggle-featured');
        Route::patch('events/{event}/toggle-active', [AdminEventController::class, 'toggleActive'])->name('events.toggle-active');
        Route::post('galleries/bulk-upload', [AdminGalleryController::class, 'bulkUpload'])->name('galleries.bulk-upload');
        Route::patch('galleries/reorder', [AdminGalleryController::class, 'reorder'])->name('galleries.reorder');
        Route::patch('galleries/{gallery}/toggle-featured', [AdminGalleryController::class, 'toggleFeatured'])->name('galleries.toggle-featured');
        Route::patch('articles/{article}/publish', [AdminArticleController::class, 'publish'])->name('articles.publish');
        Route::patch('articles/{article}/unpublish', [AdminArticleController::class, 'unpublish'])->name('articles.unpublish');
        Route::patch('testimonials/{testimonial}/toggle', [AdminTestimonialController::class, 'toggle'])->name('testimonials.toggle');

        Route::resource('banners', AdminBannerController::class);
        Route::resource('events', AdminEventController::class);
        Route::resource('galleries', AdminGalleryController::class);
        Route::resource('articles', AdminArticleController::class);
        Route::resource('testimonials', AdminTestimonialController::class);

        Route::get('products/{product}/delete', [AdminProductController::class, 'confirmDelete'])->name('products.confirm-delete');
        Route::resource('products', AdminProductController::class);

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::get('about', [AboutController::class, 'edit'])->name('about.edit');
        Route::post('about', [AboutController::class, 'update'])->name('about.update');

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
            Route::patch('/{user}/role', [AdminUserController::class, 'updateRole'])->name('update-role');
            Route::patch('/{user}/ban', [AdminUserController::class, 'ban'])->name('ban');
            Route::patch('/{user}/unban', [AdminUserController::class, 'unban'])->name('unban');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| Forum routes
|--------------------------------------------------------------------------
*/

Route::prefix('forum')
    ->name('forum.')
    ->group(function () {
        Route::get('/', [ForumController::class, 'index'])->name('index');

        Route::middleware(['auth', 'not-banned'])->group(function () {
            Route::get('/create', [ForumController::class, 'create'])->name('create');
            Route::post('/', [ForumController::class, 'store'])->name('store');
            Route::post('/{post}/reply', [ForumController::class, 'reply'])->name('reply');
            Route::post('/{post}/like', [ForumController::class, 'like'])->name('like');
            Route::delete('/{post}', [ForumController::class, 'destroy'])->name('destroy');
        });

        Route::get('/{post:slug}', [ForumController::class, 'show'])->name('show');
    });
