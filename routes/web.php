<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;



Route::get('/login', function () {
    return view('welcome');
});
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', function (Request $request) {
    Auth::logout(); // faz logout
    $request->session()->invalidate(); // invalida a sessão
    $request->session()->regenerateToken();
    return redirect('/login'); // redireciona para página inicial
})->name('logout');
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');
Route::post('/verify-sms', [AuthController::class, 'verifySms'])->name('verify.sms');

Route::middleware(['auth', 'verified.2fa'])->group(function () {
    Route::get('/docs', fn() => view('doc.api'))->name('docs');
});

