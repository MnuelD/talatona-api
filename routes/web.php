<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestControlller;
use App\Http\Controllers\Api\PaginaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/index', [TestControlller::class, 'index']);
///////////////////// paginas crud ///////////////////////
Route::get('/paginas', [TestControlller::class, 'paginas'])->name('test.paginas');
Route::post('/paginas/store', [PaginaController::class, 'store'])->name('test.paginas.store');










Route::get('/paginas/noticias', [TestControlller::class, 'notic']);





////////////////////////////////////////////use App\Http\Controllers\PaginaController;



