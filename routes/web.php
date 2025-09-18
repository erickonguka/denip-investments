<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientDashboardController;

// Guest routes
Route::middleware('guest')->group(function () {
    // Admin login
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/admin/auth/login', [AuthController::class, 'login'])->middleware('throttle:20,1');
    
    // Client authentication
    Route::get('/client/login', [ClientAuthController::class, 'showLogin'])->name('client.login');
    Route::post('/client/auth/login', [ClientAuthController::class, 'login'])->name('client.login.submit')->middleware('throttle:10,1');
    Route::get('/client/register', [ClientAuthController::class, 'showRegister'])->name('client.register');
    Route::post('/client/auth/register', [ClientAuthController::class, 'register'])->name('client.onboarding.submit')->middleware('throttle:5,1');
    Route::get('/client/verify-email', [ClientAuthController::class, 'showVerifyEmail'])->name('client.verify');
    Route::post('/client/verify-email', [ClientAuthController::class, 'verifyEmail'])->name('client.verify.submit')->middleware('throttle:10,1');
    Route::post('/client/resend-verification', [ClientAuthController::class, 'resendVerification'])->name('client.verify.resend')->middleware('throttle:3,1');
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');
});

// MFA setup route (accessible without full authentication)
Route::middleware('web')->group(function () {
    Route::get('/mfa/setup', [AuthController::class, 'showMfaSetup'])->name('mfa.setup');
    Route::post('/mfa/confirm', [AuthController::class, 'confirmMfa'])->name('mfa.confirm');
});

// Landing pages with proper SEO structure
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('landing.index');
Route::get('/our-projects', [\App\Http\Controllers\LandingController::class, 'projects'])->name('landing.projects');
Route::get('/projects/{project:slug}', [\App\Http\Controllers\LandingController::class, 'projectShow'])->name('projects.public');
Route::get('/about', [\App\Http\Controllers\LandingController::class, 'about'])->name('about');
Route::get('/services', [\App\Http\Controllers\LandingController::class, 'services'])->name('services');
Route::get('/contact', [\App\Http\Controllers\LandingController::class, 'contact'])->name('contact');
Route::get('/careers', [\App\Http\Controllers\LandingController::class, 'careers'])->name('careers');
Route::get('/careers/{slug}', [\App\Http\Controllers\LandingController::class, 'careerShow'])->name('landing.careers.show');
Route::get('/careers/{slug}/apply', [\App\Http\Controllers\LandingController::class, 'careerApply'])->name('landing.careers.apply');
Route::get('/blog', [\App\Http\Controllers\Landing\BlogController::class, 'index'])->name('landing.blog.index');
Route::get('/blog/{blog:slug}', [\App\Http\Controllers\Landing\BlogController::class, 'show'])->name('landing.blog.show');
Route::post('/blog/{blog:slug}/comment', [\App\Http\Controllers\Landing\BlogController::class, 'storeComment'])->name('landing.blog.comment');
Route::get('/privacy-policy', function () { return view('landing.privacy-policy'); })->name('privacy-policy');
Route::get('/terms-of-service', function () { return view('landing.terms-of-service'); })->name('terms-of-service');

// SEO Routes
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Disallow: /admin/\n";
    $content .= "Disallow: /client/\n";
    $content .= "Disallow: /dashboard\n";
    $content .= "Disallow: /login\n";
    $content .= "Disallow: /register\n";
    $content .= "\n";
    $content .= "Sitemap: " . route('sitemap') . "\n";
    
    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('robots');

Route::get('/login', function() { return redirect()->route('client.login'); });
Route::get('/register', function() { return redirect()->route('client.register'); });

// Unified dashboard route
Route::get('/dashboard', [\App\Http\Controllers\UnifiedDashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Permission test route (remove in production)
Route::get('/test-permissions', [\App\Http\Controllers\PermissionTestController::class, 'testPermissions'])->middleware('auth');

// Debug route (remove in production)
Route::get('/debug-user', function() {
    if (!auth()->check()) {
        return 'Not authenticated';
    }
    
    $user = auth()->user();
    return [
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role_field' => $user->role,
        'status' => $user->status,
        'roles_from_pivot' => $user->roles->pluck('name'),
        'isClient' => $user->isClient(),
        'isAdmin' => $user->isAdmin(),
        'hasRole_admin' => $user->hasRole('admin'),
        'hasRole_super_admin' => $user->hasRole('super_admin'),
        'hasRole_client' => $user->hasRole('client'),
    ];
})->middleware('auth');

// Protected routes
Route::middleware(['auth', App\Http\Middleware\SecurityHeaders::class, App\Http\Middleware\EnsureMfaConfigured::class])->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/client/logout', [ClientAuthController::class, 'logout'])->name('client.logout');
    
    // Client dashboard routes - protected by ClientAccess middleware
    Route::prefix('client')->name('client.')->middleware(App\Http\Middleware\ClientAccess::class)->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        Route::resource('projects', \App\Http\Controllers\Client\ProjectController::class);
        Route::resource('invoices', \App\Http\Controllers\Client\InvoiceController::class)->only(['index', 'show']);
        Route::post('invoices/{id}/mark-paid', [\App\Http\Controllers\Client\InvoiceController::class, 'markPaid'])->name('invoices.markPaid');
        Route::get('invoices/{id}/pdf', [\App\Http\Controllers\Client\PDFController::class, 'viewInvoice'])->name('invoices.pdf');
        Route::get('invoices/{id}/download', [\App\Http\Controllers\Client\PDFController::class, 'downloadInvoice'])->name('invoices.download');
        Route::get('proposals/{id}/pdf', [\App\Http\Controllers\Client\PDFController::class, 'viewProposal'])->name('proposals.pdf');
        Route::get('proposals/{id}/download', [\App\Http\Controllers\Client\PDFController::class, 'downloadProposal'])->name('proposals.download');
        Route::resource('proposals', \App\Http\Controllers\Client\ProposalController::class)->only(['index', 'show']);
        Route::post('proposals/{id}/status', [\App\Http\Controllers\Client\ProposalController::class, 'updateStatus'])->name('proposals.updateStatus');
        Route::resource('activities', \App\Http\Controllers\Client\ActivityController::class)->only(['index']);
        Route::resource('calendar', \App\Http\Controllers\Client\CalendarController::class)->only(['index']);
        Route::post('calendar/book', [\App\Http\Controllers\Client\CalendarController::class, 'book'])->name('calendar.book');
        Route::get('calendar/bookings', [\App\Http\Controllers\Client\CalendarController::class, 'bookings'])->name('calendar.bookings');
        Route::resource('messages', \App\Http\Controllers\Client\MessageController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('messages/{message}/reply', [\App\Http\Controllers\Client\MessageController::class, 'reply'])->name('messages.reply');
        Route::get('messages/unread-count', [\App\Http\Controllers\Client\MessageController::class, 'unreadCount'])->name('messages.unread-count');
        Route::get('unread-count', [\App\Http\Controllers\Client\MessageController::class, 'unreadCount'])->name('unread-count');
        Route::get('notifications', [\App\Http\Controllers\Client\MessageController::class, 'notifications'])->name('notifications');
        Route::post('notifications/clear', [\App\Http\Controllers\Client\MessageController::class, 'clearNotifications'])->name('notifications.clear');
        Route::post('notifications/{id}/read', [\App\Http\Controllers\Client\MessageController::class, 'markAsRead'])->name('notifications.read');
        
        // MFA routes
        Route::post('mfa/enable', [\App\Http\Controllers\Client\MfaController::class, 'enable'])->name('mfa.enable');
        Route::post('mfa/confirm', [\App\Http\Controllers\Client\MfaController::class, 'confirm'])->name('mfa.confirm');
        Route::post('mfa/disable', [\App\Http\Controllers\Client\MfaController::class, 'disable'])->name('mfa.disable');
        
        // Profile routes
        Route::get('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'show'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/password/change', [\App\Http\Controllers\Client\ProfileController::class, 'changePassword'])->name('password.change');
    });
    
    Route::get('/account', [AuthController::class, 'account'])->name('account');
    Route::put('/account/update', [AuthController::class, 'updateAccount'])->name('account.update');
    
    // MFA routes (for authenticated users)
    Route::post('/mfa/enable', [AuthController::class, 'enableMfa'])->name('mfa.enable');
    Route::post('/mfa/disable', [AuthController::class, 'disableMfa'])->name('mfa.disable');

    // Admin dashboard - requires admin access
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard')->middleware(App\Http\Middleware\AdminAccess::class);
    
    // Admin resource routes - require admin access
    Route::middleware(App\Http\Middleware\AdminAccess::class)->group(function () {
        Route::resource('clients', ClientController::class);
        Route::resource('projects', ProjectController::class);
        Route::resource('invoices', InvoiceController::class);
        Route::resource('proposals', ProposalController::class);
        Route::resource('quotations', QuotationController::class);
        Route::post('quotations/{quotation}/convert-to-invoice', [QuotationController::class, 'convertToInvoice']);
        
        Route::resource('users', UserController::class)->middleware('permission:users.read');
        Route::resource('roles', RoleController::class)->middleware('permission:roles.read');
        Route::resource('blogs', \App\Http\Controllers\BlogController::class);
        Route::resource('blog-categories', \App\Http\Controllers\BlogCategoryController::class);
        Route::resource('team-members', \App\Http\Controllers\TeamMemberController::class);
        Route::resource('admin/services', \App\Http\Controllers\ServiceController::class)->names([
            'index' => 'services.index',
            'create' => 'services.create',
            'store' => 'services.store',
            'show' => 'services.show',
            'edit' => 'services.edit',
            'update' => 'services.update',
            'destroy' => 'services.destroy'
        ]);
        Route::resource('admin/careers', \App\Http\Controllers\CareerController::class)->names([
            'index' => 'careers.index',
            'create' => 'careers.create', 
            'store' => 'careers.store',
            'show' => 'careers.show',
            'edit' => 'careers.edit',
            'update' => 'careers.update',
            'destroy' => 'careers.destroy'
        ]);
        Route::get('admin/careers/{career}/applications', [\App\Http\Controllers\CareerController::class, 'applications'])->name('careers.applications');
        Route::post('admin/career-applications/{application}/status', [\App\Http\Controllers\CareerController::class, 'updateApplicationStatus'])->name('career-applications.status');
        Route::get('admin/career-applications/{application}', function(\App\Models\CareerApplication $application) {
            return response()->json(['success' => true, 'data' => $application]);
        })->name('career-applications.show');

        Route::get('/activities', [ActivityLogController::class, 'index'])->name('activities.index')->middleware('permission:activity_logs.view');
        Route::get('/admin/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index']);
        Route::post('/admin/notifications/clear', [\App\Http\Controllers\Admin\NotificationController::class, 'clear']);
        Route::get('/admin/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'unreadCount']);
        
        // Admin Messages
        Route::prefix('admin/messages')->name('admin.messages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('index');
            Route::get('/chat/{userId}', [\App\Http\Controllers\Admin\MessageController::class, 'startChat'])->name('startChat');
            Route::post('/', [\App\Http\Controllers\Admin\MessageController::class, 'store'])->name('store');
            Route::get('/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'show'])->name('show');
            Route::post('/{message}/reply', [\App\Http\Controllers\Admin\MessageController::class, 'reply'])->name('reply');
            Route::post('/{message}/mark-read', [\App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/{message}/mark-unread', [\App\Http\Controllers\Admin\MessageController::class, 'markAsUnread'])->name('markAsUnread');
            Route::delete('/{message}', [\App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('destroy');
        });
        
        // Admin Calendar
        Route::prefix('admin/calendar')->name('admin.calendar.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('index');
            Route::get('/bookings', [\App\Http\Controllers\Admin\CalendarController::class, 'bookings'])->name('bookings');
        });
        
        Route::get('/admin/bookings/{booking}', [\App\Http\Controllers\Admin\CalendarController::class, 'getBooking'])->name('admin.bookings.show');
        Route::post('/admin/bookings/{booking}/status', [\App\Http\Controllers\Admin\CalendarController::class, 'updateBooking'])->name('admin.bookings.status');
        Route::post('/admin/bookings/{booking}/reschedule', [\App\Http\Controllers\Admin\CalendarController::class, 'rescheduleBooking'])->name('admin.bookings.reschedule');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index')->middleware('permission:system.settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('permission:system.settings');
        
        Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
        
        Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\PDFController::class, 'downloadInvoice'])->name('invoices.pdf');
        Route::get('/quotations/{quotation}/pdf', [\App\Http\Controllers\PDFController::class, 'downloadQuotation'])->name('quotations.pdf');
        Route::get('/proposals/{proposal}/pdf', [\App\Http\Controllers\PDFController::class, 'downloadProposal'])->name('proposals.pdf');
        Route::get('/projects/{project}/pdf', [\App\Http\Controllers\PDFController::class, 'downloadProject'])->name('projects.pdf');
        
        Route::get('/clients/{client}/documents', [\App\Http\Controllers\ClientDocumentController::class, 'getDocuments'])->name('clients.documents');
        Route::get('/clients/{client}/projects', [ClientController::class, 'getProjects'])->name('clients.projects');
        
        // Categories
        Route::resource('categories', \App\Http\Controllers\CategoryController::class)->except(['create', 'edit']);
        Route::patch('categories/{category}/toggle', [\App\Http\Controllers\CategoryController::class, 'toggle'])->name('categories.toggle');
    });
});

// Public project view
Route::get('/project/{slug}', [ProjectController::class, 'publicView'])->name('landing.project.show');
Route::get('/project/token/{token}', [ProjectController::class, 'publicViewByToken'])->name('projects.public.token');

// Career routes
Route::get('/career/{slug}', [\App\Http\Controllers\LandingController::class, 'careerShow'])->name('career.show');
Route::get('/career/{slug}/apply', [\App\Http\Controllers\LandingController::class, 'careerApply'])->name('career.apply');

// Public document verification
Route::get('/verify/{hash}', [\App\Http\Controllers\DocumentVerificationController::class, 'verify'])->name('verify.document');

// Contact form submissions
Route::post('/contact/submit', [\App\Http\Controllers\Landing\ContactController::class, 'submitContact'])->name('contact.submit');
Route::post('/quote/submit', [\App\Http\Controllers\Landing\ContactController::class, 'submitQuote'])->name('quote.submit');
Route::post('/careers/apply', [\App\Http\Controllers\ContactController::class, 'submitApplication'])->name('careers.apply.submit');

// Unified PDF viewing (accessible by both admin and client)
Route::middleware('auth')->group(function () {
    Route::get('/documents/invoice/{id}/view', [\App\Http\Controllers\UnifiedPDFController::class, 'viewInvoice'])->name('documents.invoice.view');
    Route::get('/documents/proposal/{id}/view', [\App\Http\Controllers\UnifiedPDFController::class, 'viewProposal'])->name('documents.proposal.view');
});

// Debug admin access (remove in production)
Route::get('/debug-admin', function() {
    if (!auth()->check()) {
        return 'Not authenticated';
    }
    
    $user = auth()->user();
    
    if ($user->status !== 'active') {
        return 'User status: ' . $user->status;
    }
    
    if ($user->isClient()) {
        return 'User is identified as client';
    }
    
    if (!$user->isAdmin()) {
        return 'User is not identified as admin';
    }
    
    return 'User should have admin access';
})->middleware('auth');


