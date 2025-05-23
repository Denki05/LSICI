<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\ApiConsumerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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

Route::get('/cameraQr', function () {
    return view('admin.camera_qr');
});

// Route yang bisa diakses publik (tanpa login)
Route::get('/admin/rsvp/page/{slug}', [RsvpController::class, 'page_invitation'])->name('admin.rsvp.page');
Route::post('/admin/rsvp/updateInvitation/{id}', [RsvpController::class, 'updateInvitation'])->name('admin.updateInvitation');
Route::post('/admin/storeFromQr', [GuestController::class, 'storeFromQr'])->name('guest.storeFromQr');

// Dashboard Admin (daftar tamu)
Route::get('/admin/guests', [GuestController::class, 'index'])->middleware('auth');

Route::middleware(['auth', 'admin'])->group(function () {
    // Guest
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::delete('/admin/guest/{id}', [AdminController::class, 'deleteGuest'])->name('admin.guest.delete');
    Route::post('/admin/guest/{id}/upload-photo', [AdminController::class, 'uploadPhoto'])->name('admin.guest.upload_photo');
    Route::get('/admin/guest/export', [AdminController::class, 'export'])->name('admin.guest.export');
    Route::post('/admin/guest/import', [AdminController::class, 'import'])->name('admin.guest.import');
    
    // RSVP
    Route::get('/admin/rsvp/export_guests', [RsvpController::class, 'export_guests'])->name('admin.export_guests');

    // Perbaikan: ubah URL RSVP agar tidak tumpang tindih
    Route::get('/admin/rsvp', [RsvpController::class, 'index'])->name('admin.rsvp');
    Route::get('/admin/rsvp/create', [RsvpController::class, 'create'])->name('admin.create');
    Route::post('/admin/rsvp/store', [RsvpController::class, 'store'])->name('admin.store');
    Route::get('/admin/rsvp/export', [RsvpController::class, 'export'])->name('admin.export');
    Route::post('/admin/rsvp/import', [RsvpController::class, 'import'])->name('admin.import');
    Route::get('/admin/rsvp/generateInvitation/{id}', [RsvpController::class, 'generateInvitation'])->name('admin.generateInvitation');
    Route::delete('/admin/rsvp/delete/{id}', [RsvpController::class, 'delete'])->name('admin.rsvp.delete');
    Route::get('/admin/rsvp/exportAttendance', [RsvpController::class, 'exportAttendance'])->name('admin.exportAttendance');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/qrcodes/{filename}', function ($filename) {
    $path = public_path($filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
})->where('filename', '.*')->name('qr.show');

Route::get('/rsvp/{filename}', function ($filename) {
    $path = public_path('rsvp/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
})->where('filename', '.*')->name('rsvp.image');