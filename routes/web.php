<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\MedicalRecordController;
use App\Http\Controllers\Admin\QueueController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PatientAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Frontend Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/layanan', [HomeController::class, 'services'])->name('services');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/antrean', [HomeController::class, 'queue'])->name('patient.queue');

// Patient Auth Routes
Route::get('/masuk', [PatientAuthController::class, 'showLoginForm'])->name('patient.login');
Route::post('/masuk', [PatientAuthController::class, 'login'])->name('patient.login.post');
Route::get('/daftar', [PatientAuthController::class, 'showRegisterForm'])->name('patient.register');
Route::post('/daftar', [PatientAuthController::class, 'register'])->name('patient.register.post');
Route::post('/keluar-pasien', [PatientAuthController::class, 'logout'])->name('patient.logout');

// Patient Protected Routes
Route::middleware(['auth:patient'])->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/rekam-medis', [PatientAuthController::class, 'records'])->name('records');
    Route::get('/rekam-medis/{record}', [PatientAuthController::class, 'showRecord'])->name('records.show');
    Route::get('/jadwal', [PatientAuthController::class, 'appointments'])->name('appointments');
    Route::get('/invoice', [PatientAuthController::class, 'invoices'])->name('invoices');
    Route::get('/invoice/{invoice}', [PatientAuthController::class, 'showInvoice'])->name('invoices.show');
    Route::get('/profil', [PatientAuthController::class, 'profile'])->name('profile');
    Route::post('/profil', [PatientAuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/notifikasi', [PatientAuthController::class, 'notifications'])->name('notifications');
});

// Admin Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global Search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Patients
    Route::resource('patients', PatientController::class);

    // Medical Records
    Route::resource('medical-records', MedicalRecordController::class);

    // Queues
    Route::resource('queues', QueueController::class);
    Route::post('queues/{queue}/call', [QueueController::class, 'call'])->name('queues.call');
    Route::post('queues/{queue}/finish', [QueueController::class, 'finish'])->name('queues.finish');

    // Billing
    Route::resource('billing', BillingController::class);
    Route::post('billing/{invoice}/pay', [BillingController::class, 'markAsPaid'])->name('billing.pay');
    Route::get('billing/{invoice}/print', [BillingController::class, 'print'])->name('billing.print');

    // Inventory
    Route::resource('inventory', InventoryController::class);
    Route::post('inventory/{inventory}/add-stock', [InventoryController::class, 'addStock'])->name('inventory.add-stock');
    Route::post('inventory/{inventory}/reduce-stock', [InventoryController::class, 'reduceStock'])->name('inventory.reduce-stock');

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('reports/cohort', [ReportController::class, 'cohort'])->name('reports.cohort');
    Route::get('reports/export/{type}', [ReportController::class, 'export'])->name('reports.export');

    // User Management (Bidan can manage staff)
    Route::middleware(['can:manage-staff'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('settings/profile', [SettingController::class, 'profile'])->name('settings.profile');
    Route::post('settings/profile', [SettingController::class, 'updateProfile'])->name('settings.profile.update');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
