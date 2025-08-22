<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaginaController;
use App\Http\Controllers\Api\BtnController;
use App\Http\Controllers\Api\DestaqueController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\NoticiaController;
use App\Http\Controllers\Api\AnexoNoticiaController;
use App\Http\Controllers\Api\ComunaController;
use App\Http\Controllers\Api\BairroController;
use App\Http\Controllers\Api\MunicipeController;
use App\Http\Controllers\Api\TipoOcorrenciaController;
use App\Http\Controllers\Api\OcorrenciaController;
use App\Http\Controllers\Api\OcorrenciaAnexoController;
use App\Http\Controllers\Api\DireccaoController;
use App\Http\Controllers\Api\FuncionarioController;
use App\Http\Controllers\Api\TicketController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/verify-2fa/{token}', [AuthController::class, 'verify2FA']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    // Apenas quem tem o papel "admin" pode acessar essas
    Route::middleware('role:admin')->group(function () {
        //Route::post('/logout', [AuthController::class, 'logout']);

    });
});


/// rotas para paginas crudamente editaveis pelo admin ///
Route::get('/paginas', [PaginaController::class, 'index']);
Route::post('/paginas', [PaginaController::class, 'store']);
Route::get('/paginas/{id}', [PaginaController::class, 'show']);
Route::post('/paginas/update/{id}', [PaginaController::class, 'update']);
Route::post('/paginas/delete/{id}', [PaginaController::class, 'destroy']);

//// rotas para botões crudamente editaveis pelo admin ///
Route::get('/botoes', [BtnController::class, 'index']);
Route::post('/botoes', [BtnController::class, 'store']);
Route::get('/botoes/{id}', [BtnController::class, 'show']);
Route::post('/botoes/update/{id}', [BtnController::class, 'update']);
Route::post('/botoes/delete/{id}', [BtnController::class, 'destroy']);

/// rotas para destaques crudamente editaveis pelo admin ///
Route::get('/destaques', [DestaqueController::class, 'index']);
Route::post('/destaques', [DestaqueController::class, 'store']);
Route::get('/destaques/{id}', [DestaqueController::class, 'show']);
Route::post('/destaques/update/{id}', [DestaqueController::class    , 'update']);
Route::post('/destaques/delete/{id}', [DestaqueController::class, 'destroy']);

/// rotas para categorias crudamente editaveis pelo admin ///
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::post('/categorias', [CategoriaController::class, 'store']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
Route::post('/categorias/update/{id}', [CategoriaController::class, 'update']);
Route::post('/categorias/delete/{id}', [CategoriaController::class, 'destroy']);

/// rotas para noticias crudamente editaveis pelo admin ///
Route::get('/noticias', [NoticiaController::class, 'index']);
Route::post('/noticias', [NoticiaController::class, 'store']);
Route::get('/noticias/{id}', [NoticiaController::class, 'show']);
Route::post('/noticias/update/{id}', [NoticiaController::class, 'update']);
Route::post('/noticias/delete/{id}', [NoticiaController::class, 'destroy']);

/// rotas para anexos de noticias crudamente editaveis pelo admin ///
Route::get('/anexos-noticias', [AnexoNoticiaController::class, 'index']);
Route::post('/anexos-noticias', [AnexoNoticiaController::class, 'store']);
Route::get('/anexos-noticias/{id}', [AnexoNoticiaController::class, 'show']);
Route::post('/anexos-noticias/update/{id}', [AnexoNoticiaController::class, 'update']);
Route::post('/anexos-noticias/delete/{id}', [AnexoNoticiaController::class, 'destroy']);

/// rotas para comunas crudamente editaveis pelo admin ///
Route::get('/comunas', [ComunaController::class, 'index']);
Route::post('/comunas', [ComunaController::class, 'store']);
Route::get('/comunas/{id}', [ComunaController::class, 'show']);
Route::post('/comunas/update/{id}', [ComunaController::class, 'update']);
Route::post('/comunas/delete/{id}', [ComunaController::class, 'destroy']);

/// rotas para bairros crudamente editaveis pelo admin ///
Route::get('/bairros', [BairroController::class, 'index']);
Route::post('/bairros', [BairroController::class, 'store']);
Route::get('/bairros/{id}', [BairroController::class, 'show']);
Route::post('/bairros/update/{id}', [BairroController::class, 'update']);
Route::post('/bairros/delete/{id}', [BairroController::class, 'destroy']);

/// rotas para municipes crudamente editaveis pelo admin ///
Route::get('/municipes', [MunicipeController::class, 'index']);
Route::post('/municipes', [MunicipeController::class, 'store']);
Route::get('/municipes/{id}', [MunicipeController::class, 'show']);
Route::post('/municipes/update/{id}', [MunicipeController::class, 'update']);
Route::post('/municipes/delete/{id}', [MunicipeController::class, 'destroy']);

/// rotas para tipos de ocorrencias crudamente editaveis pelo admin ///
Route::get('/tipos-ocorrencias', [TipoOcorrenciaController::class, 'index']);
Route::post('/tipos-ocorrencias', [TipoOcorrenciaController::class, 'store']);
Route::get('/tipos-ocorrencias/{id}', [TipoOcorrenciaController::class, 'show']);
Route::post('/tipos-ocorrencias/update/{id}', [TipoOcorrenciaController::class, 'update']);
Route::post('/tipos-ocorrencias/delete/{id}', [TipoOcorrenciaController::class, 'destroy']);

/// rotas para ocorrencias crudamente editaveis pelo admin ///
Route::get('/ocorrencias', [OcorrenciaController::class, 'index']);
Route::post('/ocorrencias', [OcorrenciaController::class, 'store']);
Route::get('/ocorrencias/{id}', [OcorrenciaController::class, 'show']);
Route::post('/ocorrencias/update/{id}', [OcorrenciaController::class    , 'update']);
Route::post('/ocorrencias/delete/{id}', [OcorrenciaController::class, 'destroy']);

// rotas para anexos de ocorrencias crudamente editaveis pelo admin ///
Route::get('/anexos-ocorrencias', [OcorrenciaAnexoController::class, 'index']);
Route::post('/anexos-ocorrencias', [OcorrenciaAnexoController::class, 'store']);
Route::get('/anexos-ocorrencias/{id}', [OcorrenciaAnexoController::class, 'show']);
Route::post('/anexos-ocorrencias/update/{id}', [OcorrenciaAnexoController::class, 'update']);
Route::post('/anexos-ocorrencias/delete/{id}', [OcorrenciaAnexoController::class, 'destroy']);

// rotas para direccao crudamente editaveis pelo admin ///
Route::get('/direccao', [DireccaoController::class, 'index']);
Route::post('/direccao', [DireccaoController::class, 'store']);
Route::get('/direccao/{id}', [DireccaoController::class, 'show']);
Route::post('/direccao/update/{id}', [DireccaoController::class, 'update']);
Route::post('/direccao/delete/{id}', [DireccaoController::class, 'destroy']);

// rotas para funcionarios crudamente editaveis pelo admin ///
Route::get('/funcionarios', [FuncionarioController::class, 'index']);
Route::post('/funcionarios', [FuncionarioController::class, 'store']);
Route::get('/funcionarios/{id}', [FuncionarioController::class, 'show']);
Route::post('/funcionarios/update/{id}', [FuncionarioController::class, 'update']);
Route::post('/funcionarios/delete/{id}', [FuncionarioController::class, 'destroy']);

// rotas para tickets crudamente editaveis pelo admin ///
Route::get('/tickets', [TicketController::class, 'index']);
Route::post('/tickets', [TicketController::class, 'store']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);
Route::post('/tickets/update/{id}', [TicketController::class, 'update']);
Route::post('/tickets/delete/{id}', [TicketController::class, 'destroy']);

