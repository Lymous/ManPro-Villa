<?php

use App\Http\Controllers\Admin\BlockedDateController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentManagementController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\RoomManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\TripayCallbackController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Guest Routes (Nginapsini.id Style)
|--------------------------------------------------------------------------
*/
Route::get('/', [BookingController::class, 'index'])->name('home');
Route::get('/paket', [BookingController::class, 'packages'])->name('packages.index');
Route::get('/paket/{slug}', [BookingController::class, 'packageDetail'])->name('packages.show');
// Reservasi & Checkout (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/{slug}', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/booking/store', [BookingController::class, 'storeBooking'])->name('booking.store');
});

Route::get('/invoice/{kodeBooking}', [BookingController::class, 'invoice'])->name('booking.invoice');
Route::post('/invoice/{kodeBooking}/upload-proof', [BookingController::class, 'uploadManualProof'])->name('booking.upload_proof');
Route::get('/lacak-pesanan', [BookingController::class, 'trackBooking'])
    ->middleware('throttle:20,1')
    ->name('booking.track');
Route::get('/tentang-kami', function () {
    return view('pages.about');
})->name('about');

// Tripay Sandbox Callback (CSRF Dikecualikan di bootstrap/app.php)
Route::post('/api/tripay/callback', [TripayCallbackController::class, 'handle'])->name('tripay.callback');
Route::post('/tripay/callback', [TripayCallbackController::class, 'handle']);

// Tripay Instant Simulator - HANYA aktif di local environment untuk Pengelola Villa
if (app()->environment('local')) {
    Route::get('/tripay/simulate-paid/{reference}', [TripayCallbackController::class, 'simulatePaid'])
        ->middleware(['auth', 'role:admin,penjaga'])
        ->name('tripay.simulate_paid');
}

/*
|--------------------------------------------------------------------------
| Authentication (Customer & Staff)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Portal Tamu / Customer (Pesanan Saya)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings');
    Route::post('/profile', [CustomerBookingController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Panel Pengelola (Admin & Penjaga Villa)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,penjaga'])->group(function () {
    // Dashboard & Kalender Operasional (Bisa diakses Admin & Penjaga)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pemesanan & Penugasan Kamar Fisik (Bisa diakses Admin & Penjaga)
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingManagementController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingManagementController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/assign-room', [BookingManagementController::class, 'assignRoom'])->name('bookings.assign_room');
    Route::post('/bookings/{id}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.update_status');
    Route::post('/bookings/{id}/reschedule', [BookingManagementController::class, 'reschedule'])->name('bookings.reschedule');

    // Akses Khusus Admin (Keuangan, Kebijakan, Blokir Tanggal, Master Kamar)
    Route::middleware(['role:admin'])->group(function () {
        // Pembayaran & Verifikasi
        Route::get('/payments', [PaymentManagementController::class, 'index'])->name('payments.index');
        Route::post('/payments/{id}/verify', [PaymentManagementController::class, 'verify'])->name('payments.verify');
        Route::post('/payments/{id}/reject', [PaymentManagementController::class, 'reject'])->name('payments.reject');
        Route::post('/payments/settlement/{bookingId}', [PaymentManagementController::class, 'createSettlement'])->name('payments.settlement');

        // Blokir Tanggal Pemeliharaan
        Route::get('/blocked-dates', [BlockedDateController::class, 'index'])->name('blocked_dates.index');
        Route::post('/blocked-dates', [BlockedDateController::class, 'store'])->name('blocked_dates.store');
        Route::delete('/blocked-dates/{id}', [BlockedDateController::class, 'destroy'])->name('blocked_dates.destroy');

        // Manajemen Kamar & Paket
        Route::get('/rooms', [RoomManagementController::class, 'index'])->name('rooms.index');
        Route::get('/rooms/type/{id}/edit', [RoomManagementController::class, 'editType'])->name('rooms.edit_type');
        Route::put('/rooms/type/{id}', [RoomManagementController::class, 'updateType'])->name('rooms.update_type');
        Route::post('/rooms/type/{id}/photos', [RoomManagementController::class, 'uploadPhotos'])->name('rooms.upload_photos');
        Route::post('/rooms/photos/{id}/set-primary', [RoomManagementController::class, 'setPrimaryPhoto'])->name('rooms.set_primary_photo');
        Route::delete('/rooms/photos/{id}', [RoomManagementController::class, 'deletePhoto'])->name('rooms.delete_photo');
        Route::post('/rooms/store-room', [RoomManagementController::class, 'storeRoom'])->name('rooms.store_room');
        Route::post('/rooms/{id}/status', [RoomManagementController::class, 'updateRoomStatus'])->name('rooms.update_room_status');

        // Kebijakan Bisnis
        Route::get('/policies', [PolicyController::class, 'index'])->name('policies.index');
        Route::post('/policies', [PolicyController::class, 'update'])->name('policies.update');
    });
});
