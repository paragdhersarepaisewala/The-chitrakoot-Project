<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SevaController;
use App\Http\Controllers\ChitrakootController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;

// Public Website Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit']);

// Seva Projects Routes
Route::get('/seva', [SevaController::class, 'index'])->name('seva.index');
Route::get('/seva/{slug}', [SevaController::class, 'show'])->name('seva.show');

// Chitrakoot Guide Routes
Route::get('/chitrakoot', [ChitrakootController::class, 'index'])->name('chitrakoot.index');
Route::get('/chitrakoot/{slug}', [ChitrakootController::class, 'show'])->name('chitrakoot.show');

// Events Routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{id}/like', [BlogController::class, 'like'])->name('blog.like');
Route::post('/blog/{id}/comment', [BlogController::class, 'comment'])->name('blog.comment');

// Gallery Magazine Route
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Donation & Receipts Routes
Route::get('/donate', [DonationController::class, 'index'])->name('donate');
Route::get('/receipt/{receiptNumber}', [DonationController::class, 'showReceipt'])->name('receipt.show');

// API Endpoints for Razorpay Checkout
Route::post('/api/donations/create-order', [DonationController::class, 'createOrder']);
Route::post('/api/donations/verify-payment', [DonationController::class, 'verifyPayment']);

// Registration Step-by-Step & Auth Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register/step-1', [AuthController::class, 'registerStep1']);
Route::post('/register/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/register/complete-profile', [AuthController::class, 'completeProfile']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Authenticated User & Admin Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/card', [CardController::class, 'show'])->name('user.card');

    // Admin Portal
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/sevas', [AdminController::class, 'sevas'])->name('admin.sevas');
        Route::post('/sevas', [AdminController::class, 'storeSeva']);
        Route::delete('/sevas/{id}', [AdminController::class, 'deleteSeva']);

        Route::get('/destinations', [AdminController::class, 'destinations'])->name('admin.destinations');
        Route::post('/destinations', [AdminController::class, 'storeDestination']);
        Route::delete('/destinations/{id}', [AdminController::class, 'deleteDestination']);

        Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
        Route::post('/blogs', [AdminController::class, 'storeBlog']);
        Route::delete('/blogs/{id}', [AdminController::class, 'deleteBlog']);

        Route::get('/causes', [AdminController::class, 'causes'])->name('admin.causes');
        Route::post('/causes', [AdminController::class, 'storeCause']);
        Route::delete('/causes/{id}', [AdminController::class, 'deleteCause']);

        Route::get('/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');
        Route::post('/gallery', [AdminController::class, 'storeGalleryItem']);
        Route::delete('/gallery/{id}', [AdminController::class, 'deleteGalleryItem']);

        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole']);

        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/settings', [AdminController::class, 'updateSettings']);
    });
});
