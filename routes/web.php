<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;



Route::get('/', function () {
    return view('welcome');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');
Route::post('/verify-sms', [AuthController::class, 'verifySms'])->name('verify.sms');

Route::middleware(['auth', 'verified.2fa'])->group(function () {
    Route::get('/docs', fn() => view('docs'))->name('docs');
});

