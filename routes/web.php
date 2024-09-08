<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduledClassController;
use App\Http\Controllers\StripeWebhookController;
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

Route::get('orders/{order}/pay',[\App\Http\Controllers\Front\PaymentsController::class,'create'])->name('orders.payments.create');
Route::post('orders/{order}/stripe/payment-intent',[\App\Http\Controllers\Front\PaymentsController::class,'createStripePaymentIntent'])->name('stripe.paymentIntent.create');

Route::get('orders/{order}/pay/stripe/callback',[\App\Http\Controllers\Front\PaymentsController::class,'confirm'])->name('stripe.return');

Route::any('stripe/webhook',[\App\Http\Controllers\StripeWebhooksController::class,'handle']);

require __DIR__.'/auth.php';
