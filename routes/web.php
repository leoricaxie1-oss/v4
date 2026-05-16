<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\AddressLookupController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Captain\CaptainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Kagawad\KagawadController;
use App\Http\Controllers\Public\DirectoryController;
use App\Http\Controllers\Public\LandingController;
use App\Http\Controllers\Resident\AppointmentController;
use App\Http\Controllers\Resident\ComplaintController;
use App\Http\Controllers\Resident\DashboardController as ResidentDashboardController;
use App\Http\Controllers\Resident\DocumentController;
use App\Http\Controllers\Resident\MessageController;
use App\Http\Controllers\Resident\NotificationController;
use App\Http\Controllers\Resident\ProfileController;
use App\Http\Controllers\Resident\SkillServiceController;
use App\Http\Controllers\Secretary\SecretaryController;
use App\Http\Controllers\Tanod\TanodController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/',         [LandingController::class, 'index'])->name('home');
Route::get('/about',    [LandingController::class, 'about'])->name('about');
Route::get('/contact',  [LandingController::class, 'contact'])->name('contact');

Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');
Route::get('/directory/services/{service}', [DirectoryController::class, 'showService'])->name('directory.service');
Route::get('/directory/businesses/{business}', [DirectoryController::class, 'showBusiness'])->name('directory.business');

/*
|--------------------------------------------------------------------------
| Address lookups (dependent dropdowns)
|--------------------------------------------------------------------------
*/
Route::prefix('lookup')->group(function () {
    Route::get('cities/{province}',     [AddressLookupController::class, 'cities']);
    Route::get('barangays/{city}',      [AddressLookupController::class, 'barangays']);
    Route::get('puroks/{barangay}',     [AddressLookupController::class, 'puroks']);
});

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login',  [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated app
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/account/pending', [DashboardController::class, 'pending'])->name('account.pending');

    // ----- Resident area --------------------------------------------------
    Route::middleware(['verified.resident'])->prefix('resident')->name('resident.')->group(function () {
        Route::get('/', [ResidentDashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile',  [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'password'])->name('password.update');

        Route::resource('documents',  DocumentController::class)
            ->only(['index', 'create', 'store', 'show', 'destroy']);

        Route::resource('complaints', ComplaintController::class)
            ->only(['index', 'create', 'store', 'show']);

        Route::resource('skills', SkillServiceController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        Route::resource('appointments', AppointmentController::class)
            ->only(['index', 'create', 'store']);

        Route::get('/messages',                       [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{conversation}',        [MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{conversation}',       [MessageController::class, 'send'])->name('messages.send');

        Route::get('/notifications',           [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::post('/notifications/{id}/read',[NotificationController::class, 'read'])->name('notifications.read');
    });

    // ----- Secretary ------------------------------------------------------
    Route::middleware('role:secretary|admin')->prefix('secretary')->name('secretary.')->group(function () {
        Route::get('/',                                  [SecretaryController::class, 'dashboard'])->name('dashboard');

        Route::get('/residents',                         [SecretaryController::class, 'residents'])->name('residents');
        Route::post('/residents/{user}/verify',          [SecretaryController::class, 'verifyResident'])->name('residents.verify');
        Route::post('/residents/{user}/reject',          [SecretaryController::class, 'rejectResident'])->name('residents.reject');

        Route::get('/documents',                         [SecretaryController::class, 'documents'])->name('documents');
        Route::post('/documents/{document}/approve',     [SecretaryController::class, 'approveDocument'])->name('documents.approve');
        Route::post('/documents/{document}/reject',      [SecretaryController::class, 'rejectDocument'])->name('documents.reject');
        Route::post('/documents/{document}/release',     [SecretaryController::class, 'releaseDocument'])->name('documents.release');

        Route::get('/skills',                            [SecretaryController::class, 'skills'])->name('skills');
        Route::post('/skills/{skill}/approve',           [SecretaryController::class, 'approveSkill'])->name('skills.approve');

        Route::get('/businesses',                        [SecretaryController::class, 'businesses'])->name('businesses');
        Route::post('/businesses/{business}/approve',    [SecretaryController::class, 'approveBusiness'])->name('businesses.approve');
    });

    // ----- Kagawad --------------------------------------------------------
    Route::middleware('role:kagawad|admin')->prefix('kagawad')->name('kagawad.')->group(function () {
        Route::get('/',                                  [KagawadController::class, 'dashboard'])->name('dashboard');
        Route::get('/residents',                         [KagawadController::class, 'residents'])->name('residents');
        Route::post('/residents/{user}/approve',         [KagawadController::class, 'approveResident'])->name('residents.approve');
        Route::get('/skills',                            [KagawadController::class, 'skills'])->name('skills');
        Route::post('/skills/{skill}/approve',           [KagawadController::class, 'approveSkill'])->name('skills.approve');
        Route::get('/businesses',                        [KagawadController::class, 'businesses'])->name('businesses');
        Route::post('/businesses/{business}/approve',    [KagawadController::class, 'approveBusiness'])->name('businesses.approve');
    });

    // ----- Captain --------------------------------------------------------
    Route::middleware('role:captain|admin')->prefix('captain')->name('captain.')->group(function () {
        Route::get('/',                                  [CaptainController::class, 'dashboard'])->name('dashboard');
        Route::get('/residents',                         [CaptainController::class, 'residents'])->name('residents');
        Route::post('/residents/{user}/approve',         [CaptainController::class, 'approveResident'])->name('residents.approve');
        Route::get('/skills',                            [CaptainController::class, 'skills'])->name('skills');
        Route::post('/skills/{skill}/approve',           [CaptainController::class, 'approveSkill'])->name('skills.approve');
        Route::get('/businesses',                        [CaptainController::class, 'businesses'])->name('businesses');
        Route::post('/businesses/{business}/approve',    [CaptainController::class, 'approveBusiness'])->name('businesses.approve');
    });

    // ----- Tanod ----------------------------------------------------------
    Route::middleware('role:tanod|admin')->prefix('tanod')->name('tanod.')->group(function () {
        Route::get('/',                                  [TanodController::class, 'dashboard'])->name('dashboard');
        Route::get('/complaints',                        [TanodController::class, 'complaints'])->name('complaints');
        Route::get('/complaints/{complaint}',            [TanodController::class, 'reviewComplaint'])->name('complaints.show');
        Route::post('/complaints/{complaint}/status',    [TanodController::class, 'updateStatus'])->name('complaints.status');
        Route::post('/complaints/{complaint}/mediation', [TanodController::class, 'scheduleMediation'])->name('complaints.mediation');
        Route::post('/complaints/{complaint}/hearing',   [TanodController::class, 'scheduleHearing'])->name('complaints.hearing');
        Route::get('/blotter',                           [TanodController::class, 'blotterIndex'])->name('blotter');
        Route::post('/blotter',                          [TanodController::class, 'blotterStore'])->name('blotter.store');
    });

    // ----- Admin ----------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/',                                  [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',                             [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/role',                [AdminController::class, 'assignRole'])->name('users.role');
        Route::post('/users/{user}/suspend',             [AdminController::class, 'toggleSuspend'])->name('users.suspend');
        Route::get('/roles',                             [AdminController::class, 'roles'])->name('roles');
        Route::get('/activity-logs',                     [AdminController::class, 'activityLogs'])->name('activity');
        Route::get('/audit-logs',                        [AdminController::class, 'auditLogs'])->name('audit');

        Route::resource('announcements', AdminAnnouncementController::class)
            ->except(['show'])->parameters(['announcements' => 'announcement']);
    });

    // ----- Analytics (Captain / Kagawad / Admin) --------------------------
    Route::middleware('role:admin|captain|kagawad')->get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
});
