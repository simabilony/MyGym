<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduledClassController;
use App\Http\Controllers\StripeWebhooksController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
 Route::resource('/instructor/schedule', ScheduledClassController::class)
     ->only(['index','create','store','destroy'])
     ->middleware(['auth', 'role:instructor']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/instructor/dashboard', function () {
return view('instructor.dashboard');
})->middleware(['auth', 'role:instructor'])->name('instructor.dashboard');

Route::get('/member/dashboard', function () {
    return view('member.dashboard');
})->middleware(['auth', 'role:member'])->name('member.dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//Route::middleware('auth')->group(function () {
//    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
//    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
//    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
//    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
//});
Route::middleware('auth')->group(function () {
    Route::get('/schedule', [ScheduledClassController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/create', [ScheduledClassController::class, 'create'])->name('schedule.create');
    Route::post('/schedule', [ScheduledClassController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{schedule}', [ScheduledClassController::class, 'destroy'])->name('schedule.destroy');
});


Route::get('orders/{order}/pay',[\App\Http\Controllers\Front\PaymentsController::class,'create'])->name('orders.payments.create');
Route::post('orders/{order}/stripe/payment-intent',[\App\Http\Controllers\Front\PaymentsController::class,'createStripePaymentIntent'])->name('stripe.paymentIntent.create');

Route::get('orders/{order}/pay/stripe/callback',[\App\Http\Controllers\Front\PaymentsController::class,'confirm'])->name('stripe.return');

Route::any('stripe/webhook',[\App\Http\Controllers\StripeWebhooksController::class,'handle']);

require __DIR__.'/auth.php';
