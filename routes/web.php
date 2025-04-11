<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\ApiConsumerController;
use Illuminate\Support\Facades\Auth;

// Menambahkan route authentication
Auth::routes();

// API
Route::get('/customer', [ApiConsumerController::class, 'getCustomers'])->name('customer');

// Halaman utama dengan QR Code
Route::get('/', [GuestController::class, 'showQrCode']);

// Halaman formulir pengisian tamu
Route::get('/guest-form', [GuestController::class, 'showForm']);
Route::post('/guest-form', [GuestController::class, 'store']);
Route::get('/camera', function () {
    return view('camera');
});

// Route yang bisa diakses publik (tanpa login)
Route::get('/admin/rsvp/page/{slug}', [RsvpController::class, 'page_invitation'])->name('admin.rsvp.page');
Route::post('/admin/rsvp/updateInvitation/{id}', [RsvpController::class, 'updateInvitation'])->name('admin.updateInvitation');

// Dashboard Admin (daftar tamu)
Route::get('/admin/guests', [GuestController::class, 'index'])->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::delete('/admin/guest/{id}', [AdminController::class, 'deleteGuest'])->name('admin.guest.delete');

    // Perbaikan: ubah URL RSVP agar tidak tumpang tindih
    Route::get('/admin/rsvp', [RsvpController::class, 'index'])->name('admin.rsvp');
    Route::get('/admin/rsvp/export', [RsvpController::class, 'export'])->name('admin.export');
    Route::post('/admin/rsvp/import', [RsvpController::class, 'import'])->name('admin.import');
    Route::get('/admin/rsvp/generateInvitation/{id}', [RsvpController::class, 'generateInvitation'])->name('admin.generateInvitation');
    
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');