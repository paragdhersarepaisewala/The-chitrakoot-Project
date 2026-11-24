<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ChitrakootController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationCenterController;
use App\Http\Controllers\PoojaController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SevaController;
use App\Models\Destination;
use App\Models\Event;
use App\Models\PoojaService;
use App\Models\Post;
use App\Models\SevaProject;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/leadership', [HomeController::class, 'leadership'])->name('leadership');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->middleware('throttle:10,1');

// Seva Projects Routes
Route::get('/seva', [SevaController::class, 'index'])->name('seva.index');
Route::get('/seva/{slug}', [SevaController::class, 'show'])->name('seva.show');

// Chitrakoot Guide Routes
Route::get('/chitrakoot', [ChitrakootController::class, 'index'])->name('chitrakoot.index');
Route::get('/chitrakoot/{slug}', [ChitrakootController::class, 'show'])->name('chitrakoot.show');

// Events Routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// Sacred Pooja & Aarti Booking Routes
Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
Route::get('/services/aarti', [ServicesController::class, 'aarti'])->name('services.aarti');
Route::get('/services/atithi-vishram', [ServicesController::class, 'atithiVishram'])->name('services.atithiVishram');
Route::post('/services/atithi-vishram', [ServicesController::class, 'submitAtithiVishram'])->name('services.atithiVishram.submit')->middleware('throttle:10,1');
Route::get('/services/temple-booking', [ServicesController::class, 'templeBooking'])->name('services.templeBooking');
Route::post('/services/temple-booking', [ServicesController::class, 'submitTempleBooking'])->name('services.templeBooking.submit')->middleware('throttle:10,1');

Route::get('/pooja', [PoojaController::class, 'index'])->name('pooja.index');
Route::get('/pooja/book', [PoojaController::class, 'bookingForm'])->name('pooja.bookingForm');
Route::get('/pooja/{slug}', [PoojaController::class, 'show'])->name('pooja.show');
Route::post('/pooja/book', [PoojaController::class, 'book'])->name('pooja.book')->middleware('throttle:10,1');
Route::get('/pooja/confirmation/{bookingNumber}', [PoojaController::class, 'confirmation'])->name('pooja.confirmation');
Route::get('/pooja/quotation/{token}', [PoojaController::class, 'showQuotation'])->name('pooja.quotation.pay');
Route::post('/pooja/quotation/{token}/create-order', [PoojaController::class, 'createQuotationOrder'])->name('pooja.quotation.createOrder')->middleware('throttle:30,1');
Route::post('/api/pooja/quotation/{token}/create-order', [PoojaController::class, 'createQuotationOrder'])->middleware('throttle:30,1');
Route::post('/pooja/quotation/{token}/verify', [PoojaController::class, 'verifyQuotationPayment'])->name('pooja.quotation.verify')->middleware('throttle:30,1');
Route::post('/api/pooja/quotation/{token}/verify', [PoojaController::class, 'verifyQuotationPayment'])->middleware('throttle:30,1');
Route::redirect('/causes', '/seva', 301);
Route::redirect('/campaigns', '/seva', 301);

// Volunteer Seva Registration Routes
Route::get('/volunteer', [HomeController::class, 'volunteer'])->name('volunteer');
Route::post('/volunteer/apply', [HomeController::class, 'volunteerSubmit'])->name('volunteer.submit')->middleware('throttle:10,1');

// Newsletter Subscription Endpoint
Route::post('/newsletter/subscribe', [HomeController::class, 'newsletterSubscribe'])->name('newsletter.subscribe')->middleware('throttle:10,1');

// Blog Routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{id}/like', [BlogController::class, 'like'])->name('blog.like')->middleware('throttle:30,1');
Route::post('/blog/{id}/comment', [BlogController::class, 'comment'])->name('blog.comment')->middleware('throttle:15,1');

Route::get('/privacy-policy', function () {
    return view('pages.privacy');
})->name('privacy');

// RSS Feed & Sitemap XML Endpoints
Route::get('/feed.xml', function () {
    $posts = Post::where('status', 'published')->latest()->take(20)->get();

    return response()->view('pages.feed', compact('posts'))->header('Content-Type', 'text/xml; charset=utf-8');
});

Route::get('/sitemap.xml', function () {
    $posts = Post::where('status', 'published')->latest()->get();
    $sevas = SevaProject::where('is_published', true)->get();
    $destinations = Destination::where('is_published', true)->get();
    $poojas = PoojaService::where('is_active', true)->get();
    $events = Event::where('is_published', true)->get();

    return response()->view('pages.sitemap', compact('posts', 'sevas', 'destinations', 'poojas', 'events'))
        ->header('Content-Type', 'text/xml; charset=utf-8');
});

Route::get('/robots.txt', function () {
    $sitemapUrl = url('/sitemap.xml');
    $content = "User-agent: *\nDisallow: /admin/\nDisallow: /api/\nDisallow: /dashboard\nDisallow: /user/\nAllow: /\n\nSitemap: {$sitemapUrl}\n";

    return response($content, 200)->header('Content-Type', 'text/plain; charset=utf-8');
});

// Gallery Magazine Route
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Donation & Receipts Routes
Route::get('/donate', [DonationController::class, 'index'])->name('donate');
Route::get('/receipt/{receiptNumber}', [DonationController::class, 'showReceipt'])->name('receipt.show');

// API Endpoints for Razorpay Checkout
Route::post('/api/donations/create-order', [DonationController::class, 'createOrder'])->middleware('throttle:30,1');
Route::post('/api/donations/verify-payment', [DonationController::class, 'verifyPayment'])->middleware('throttle:30,1');
Route::post('/api/donations/webhook', [DonationController::class, 'handleWebhook'])->name('donations.webhook')->middleware('throttle:60,1');

// Registration Step-by-Step & Auth Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register/step-1', [AuthController::class, 'registerStep1'])->middleware('throttle:10,1');
Route::post('/register/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1');
Route::post('/register/resend-otp', [AuthController::class, 'resendOtp'])->name('register.resend_otp')->middleware('throttle:5,1');
Route::post('/register/complete-profile', [AuthController::class, 'completeProfile'])->middleware('throttle:10,1');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

// Password Recovery Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email')->middleware('throttle:5,1');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('throttle:5,1');

// Authenticated User & Admin Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('devotee.verified')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/user/card', [CardController::class, 'show'])->name('user.card');
        Route::get('/api/user/notifications/poll', [NotificationCenterController::class, 'userPoll'])->name('user.notifications.poll');
        Route::post('/api/user/notifications/mark-seen', [NotificationCenterController::class, 'userMarkSeen'])->name('user.notifications.markSeen');
    });

    // Admin Portal (Protected by Authentication & Admin Role Gate)
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/api/notifications/poll', [NotificationCenterController::class, 'adminPoll'])->name('admin.notifications.poll');
        Route::post('/api/notifications/mark-seen', [NotificationCenterController::class, 'adminMarkSeen'])->name('admin.notifications.markSeen');
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/donations/export-csv', [AdminController::class, 'exportDonationsCsv'])->name('admin.donations.exportCsv');
        Route::post('/flush-cache', [AdminController::class, 'flushCache'])->name('admin.flushCache');

        Route::get('/sevas', [AdminController::class, 'sevas'])->name('admin.sevas');
        Route::post('/sevas', [AdminController::class, 'storeSeva']);
        Route::put('/sevas/{id}', [AdminController::class, 'updateSeva'])->name('admin.sevas.update');
        Route::delete('/sevas/{id}', [AdminController::class, 'deleteSeva']);

        Route::get('/destinations', [AdminController::class, 'destinations'])->name('admin.destinations');
        Route::post('/destinations', [AdminController::class, 'storeDestination']);
        Route::put('/destinations/{id}', [AdminController::class, 'updateDestination'])->name('admin.destinations.update');
        Route::delete('/destinations/{id}', [AdminController::class, 'deleteDestination']);

        // Extended Blog CMS Routes
        Route::get('/blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
        Route::get('/blogs/create', [AdminController::class, 'createBlog'])->name('admin.blogs.create');
        Route::post('/blogs', [AdminController::class, 'storeBlog'])->name('admin.blogs.store');
        Route::get('/blogs/{id}/edit', [AdminController::class, 'editBlog'])->name('admin.blogs.edit');
        Route::put('/blogs/{id}', [AdminController::class, 'updateBlog'])->name('admin.blogs.update');
        Route::post('/blogs/autosave', [AdminController::class, 'autosaveBlog'])->name('admin.blogs.autosave');
        Route::delete('/blogs/{id}', [AdminController::class, 'deleteBlog'])->name('admin.blogs.delete');

        // Categories & Topics (Tags) Manager
        Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
        Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
        Route::post('/topics/delete', [AdminController::class, 'deleteTopic'])->name('admin.topics.delete');
        Route::post('/topics/rename', [AdminController::class, 'renameTopic'])->name('admin.topics.rename');

        // Comments & Moderation
        Route::get('/comments', [AdminController::class, 'comments'])->name('admin.comments');
        Route::post('/comments/{id}/approve', [AdminController::class, 'approveComment'])->name('admin.approveComment');
        Route::post('/comments/{id}/reject', [AdminController::class, 'rejectComment'])->name('admin.rejectComment');
        Route::delete('/comments/{id}', [AdminController::class, 'deleteComment'])->name('admin.deleteComment');

        Route::get('/causes', [AdminController::class, 'causes'])->name('admin.causes');
        Route::post('/causes', [AdminController::class, 'storeCause']);
        Route::put('/causes/{id}', [AdminController::class, 'updateCause'])->name('admin.causes.update');
        Route::delete('/causes/{id}', [AdminController::class, 'deleteCause']);

        Route::get('/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');
        Route::post('/gallery', [AdminController::class, 'storeGalleryItem']);
        Route::delete('/gallery/{id}', [AdminController::class, 'deleteGalleryItem']);

        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole']);

        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/settings', [AdminController::class, 'updateSettings']);

        // Homepage Layout & Components Editor (Screenshot 2)
        Route::get('/homepage-layout', [AdminController::class, 'homepageLayout'])->name('admin.homepageLayout');
        Route::post('/homepage-layout', [AdminController::class, 'updateHomepageLayout'])->name('admin.updateHomepageLayout');

        // About Us Page CMS Editor
        Route::get('/about-page', [AdminController::class, 'aboutPage'])->name('admin.about');
        Route::post('/about-page', [AdminController::class, 'updateAboutPage'])->name('admin.updateAbout');

        // Navigation Menu Manager CMS
        Route::get('/navigation-menu', [AdminController::class, 'navigationMenu'])->name('admin.navigationMenu');
        Route::post('/navigation-menu', [AdminController::class, 'storeNavigationMenuItem'])->name('admin.storeNavigationMenuItem');
        Route::put('/navigation-menu/{id}', [AdminController::class, 'updateNavigationMenuItem'])->name('admin.updateNavigationMenuItem');
        Route::delete('/navigation-menu/{id}', [AdminController::class, 'deleteNavigationMenuItem'])->name('admin.deleteNavigationMenuItem');
        Route::post('/navigation-menu/reset', [AdminController::class, 'resetNavigationMenu'])->name('admin.resetNavigationMenu');

        // Server Media Gallery Manager & Uploads (Screenshot 4)
        Route::get('/media-gallery', [AdminController::class, 'mediaGallery'])->name('admin.mediaGallery');
        Route::post('/media-gallery/upload', [AdminController::class, 'uploadMedia'])->name('admin.uploadMedia');
        Route::post('/media-gallery/batch-delete', [AdminController::class, 'batchDeleteMedia'])->name('admin.batchDeleteMedia');
        Route::delete('/media-gallery/{id}', [AdminController::class, 'deleteMedia'])->name('admin.deleteMedia');
        Route::get('/api/media-list', [AdminController::class, 'apiMediaList'])->name('admin.apiMediaList');

        // Email SMTP & Template Manager
        Route::get('/email-templates', [AdminController::class, 'emailTemplates'])->name('admin.emailTemplates');
        Route::post('/email-templates', [AdminController::class, 'storeEmailTemplate'])->name('admin.storeEmailTemplate');
        Route::get('/email-templates/{id}/preview', [AdminController::class, 'previewEmailTemplate'])->name('admin.previewEmailTemplate');
        Route::put('/email-templates/{id}', [AdminController::class, 'updateEmailTemplate'])->name('admin.updateEmailTemplate');
        Route::delete('/email-templates/{id}', [AdminController::class, 'destroyEmailTemplate'])->name('admin.destroyEmailTemplate');
        Route::post('/email-templates/toggle-service', [AdminController::class, 'toggleEmailSending'])->name('admin.toggleEmailSending');
        Route::post('/email-templates/{id}/toggle', [AdminController::class, 'toggleTemplateStatus'])->name('admin.toggleTemplateStatus');
        Route::post('/email-templates/smtp-config', [AdminController::class, 'updateSmtpConfig'])->name('admin.updateSmtpConfig');
        Route::post('/email-templates/test-smtp', [AdminController::class, 'testSmtpConnection'])->name('admin.testSmtpConnection');
        Route::get('/email-templates/logs', [AdminController::class, 'emailLogs'])->name('admin.emailLogs');
        Route::delete('/email-templates/logs/clear', [AdminController::class, 'clearEmailLogs'])->name('admin.clearEmailLogs');

        // Events & Utsavs CMS
        Route::get('/events', [AdminController::class, 'events'])->name('admin.events');
        Route::post('/events', [AdminController::class, 'storeEvent'])->name('admin.events.store');
        Route::put('/events/{id}', [AdminController::class, 'updateEvent'])->name('admin.events.update');
        Route::delete('/events/{id}', [AdminController::class, 'deleteEvent'])->name('admin.events.delete');
        Route::post('/events/auto-archive', [AdminController::class, 'autoArchiveEvents'])->name('admin.events.autoArchive');
        Route::post('/events/{id}/toggle-archive', [AdminController::class, 'toggleArchiveEvent'])->name('admin.events.toggleArchive');

        // Pooja Services & Timetable CMS
        Route::get('/poojas', [AdminController::class, 'poojas'])->name('admin.poojas');
        Route::post('/poojas', [AdminController::class, 'storePooja'])->name('admin.poojas.store');
        Route::delete('/poojas/{id}', [AdminController::class, 'deletePooja'])->name('admin.poojas.delete');
        Route::post('/poojas/toggle-packages', [AdminController::class, 'togglePoojaPackages'])->name('admin.poojas.togglePackages');
        Route::post('/poojas/aarti-timetable', [AdminController::class, 'updateAartiTimetable'])->name('admin.poojas.updateAartiTimetable');
        Route::post('/pooja-slots', [AdminController::class, 'storePoojaSlot'])->name('admin.poojaSlots.store');
        Route::delete('/pooja-slots/{id}', [AdminController::class, 'deletePoojaSlot'])->name('admin.poojaSlots.delete');
        Route::get('/pooja-bookings', [AdminController::class, 'poojaBookings'])->name('admin.poojaBookings');
        Route::post('/pooja-bookings/{id}/confirm', [AdminController::class, 'confirmPoojaBooking'])->name('admin.poojaBookings.confirm');
        Route::post('/pooja-bookings/{id}/quotation', [AdminController::class, 'sendPoojaQuotation'])->name('admin.poojaBookings.quotation');
        Route::post('/pooja-bookings/{id}/status', [AdminController::class, 'updatePoojaBookingStatus'])->name('admin.poojaBookings.status');

        // Volunteer Seva Applications CMS
        Route::get('/volunteers', [AdminController::class, 'volunteers'])->name('admin.volunteers');
        Route::post('/volunteers/toggle', [AdminController::class, 'toggleVolunteers'])->name('admin.volunteers.toggle');
        Route::post('/volunteers/{id}/status', [AdminController::class, 'updateVolunteerStatus'])->name('admin.volunteers.status');

        // Newsletter Subscribers CMS
        Route::get('/subscribers', [AdminController::class, 'subscribers'])->name('admin.subscribers');

        // All Pages SEO Settings CMS
        Route::get('/seo-settings', [AdminController::class, 'seoSettings'])->name('admin.seoSettings');
        Route::post('/seo-settings', [AdminController::class, 'updateSeoSettings'])->name('admin.updateSeoSettings');
        Route::post('/seo-settings/update-og-image', [AdminController::class, 'updatePageOgImage'])->name('admin.updatePageOgImage');

        // Contact Inquiries & Devotee Messages CMS
        Route::get('/contact-inquiries', [AdminController::class, 'contactInquiries'])->name('admin.contactInquiries');
        Route::post('/contact-inquiries/{id}/status', [AdminController::class, 'toggleContactInquiryStatus'])->name('admin.contactInquiries.status');
        Route::post('/contact-inquiries/{id}/reply', [AdminController::class, 'replyContactInquiry'])->name('admin.contactInquiries.reply');
        Route::delete('/contact-inquiries/{id}', [AdminController::class, 'deleteContactInquiry'])->name('admin.contactInquiries.delete');

        // Atithi Vishram (Pilgrim Guest House) Enquiries CMS
        Route::get('/atithi-vishram', [AdminController::class, 'atithiVishram'])->name('admin.atithiVishram');
        Route::post('/atithi-vishram/{id}/status', [AdminController::class, 'updateAtithiVishramStatus'])->name('admin.atithiVishram.status');
        Route::delete('/atithi-vishram/{id}', [AdminController::class, 'deleteAtithiVishramEnquiry'])->name('admin.atithiVishram.delete');

        // Temple Premises & Event Booking Enquiries CMS
        Route::get('/temple-bookings', [AdminController::class, 'templeBookings'])->name('admin.templeBookings');
        Route::post('/temple-bookings/{id}/status', [AdminController::class, 'updateTempleBookingStatus'])->name('admin.templeBookings.status');
        Route::delete('/temple-bookings/{id}', [AdminController::class, 'deleteTempleBookingEnquiry'])->name('admin.templeBookings.delete');

        // Leadership & Trustees CMS
        Route::get('/leadership', [AdminController::class, 'leadership'])->name('admin.leadership');
        Route::post('/leadership', [AdminController::class, 'storeLeadership'])->name('admin.leadership.store');
        Route::put('/leadership/{id}', [AdminController::class, 'updateLeadership'])->name('admin.leadership.update');
        Route::delete('/leadership/{id}', [AdminController::class, 'deleteLeadership'])->name('admin.leadership.delete');
    });
});
