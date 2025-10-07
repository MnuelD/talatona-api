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
use App\Http\Controllers\Api\IntituicaoController;




// ------------------
// AUTENTICAÇÃO
// ------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/verify-2fa/{token}', [AuthController::class, 'verify2FA']);
Route::post('/verify-sms', [AuthController::class, 'verifySms']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
});

// ------------------
// ROTAS PÚBLICAS (GET)
// ------------------

// Páginas
Route::get('/paginas', [PaginaController::class, 'index']);
Route::get('/paginas/{id}', [PaginaController::class, 'show']);

// Botões
Route::get('/botoes', [BtnController::class, 'index']);
Route::get('/botoes/{id}', [BtnController::class, 'show']);

// Destaques
Route::get('/destaques', [DestaqueController::class, 'index']);
Route::get('/destaques/{id}', [DestaqueController::class, 'show']);

// Categorias
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);

// Notícias
Route::get('/noticias', [NoticiaController::class, 'index']);
Route::get('/noticias/{id}', [NoticiaController::class, 'show']);

// Anexos Notícias
Route::get('/anexos-noticias', [AnexoNoticiaController::class, 'index']);
Route::get('/anexos-noticias/{id}', [AnexoNoticiaController::class, 'show']);
Route::get('/anexos-noticias/by-noticia/{noticia_id}', [AnexoNoticiaController::class, 'showByNoticia']);
// Comunas
Route::get('/comunas', [ComunaController::class, 'index']);
Route::get('/comunas/{id}', [ComunaController::class, 'show']);

// Bairros
Route::get('/bairros', [BairroController::class, 'index']);
Route::get('/bairros/{id}', [BairroController::class, 'show']);

// Munícipes
Route::get('/municipes', [MunicipeController::class, 'index']);
Route::get('/municipes/{id}', [MunicipeController::class, 'show']);

// Tipos Ocorrências
Route::get('/tipos-ocorrencias', [TipoOcorrenciaController::class, 'index']);
Route::get('/tipos-ocorrencias/{id}', [TipoOcorrenciaController::class, 'show']);

// Ocorrências
Route::get('/ocorrencias', [OcorrenciaController::class, 'index']);
Route::get('/ocorrencias/{id}', [OcorrenciaController::class, 'show']);
Route::get('/ocorrencias/codigo/{codigo}', [OcorrenciaController::class, 'getByCodigo']);

// Anexos Ocorrências
Route::get('/anexos-ocorrencias', [OcorrenciaAnexoController::class, 'index']);
Route::get('/anexos-ocorrencias/{id}', [OcorrenciaAnexoController::class, 'show']);
Route::get('/anexos-ocorrencias/ocorrencia/{id}', [OcorrenciaAnexoController::class, 'showByOcorrenciaId']);
Route::get('/anexos-noticias/with-noticias', [AnexoNoticiaController::class, 'indexWithNoticias']);
Route::get('/anexos-noticias/by-noticia/{noticia_id}', [AnexoNoticiaController::class, 'showByNoticia']);

// Direção
Route::get('/direccao', [DireccaoController::class, 'index']);
Route::get('/direccao/{id}', [DireccaoController::class, 'show']);

// Funcionários
Route::get('/funcionarios', [FuncionarioController::class, 'index']);
Route::get('/funcionarios/{id}', [FuncionarioController::class, 'show']);

// Tickets
Route::get('/tickets', [TicketController::class, 'index']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);


// Instituições
Route::get('/instituicao', [IntituicaoController::class, 'index']);
Route::get('/instituicao/{slug}', [IntituicaoController::class, 'show']);


// -----------------
// ROTAS ESPECIAIS (GET/POST)
Route::post('/tickets', [TicketController::class, 'store']);
// -----------------


// ------------------
// ROTAS PROTEGIDAS (POST/UPDATE/DELETE)
// ------------------
Route::middleware('auth:sanctum')->group(function () {

    // Páginas
    Route::post('/paginas', [PaginaController::class, 'store']);
    Route::put('/paginas/update/{id}', [PaginaController::class, 'update']);
    Route::delete('/paginas/delete/{id}', [PaginaController::class, 'destroy']);

    // Botões
    Route::post('/botoes', [BtnController::class, 'store']);
    Route::post('/botoes/update/{id}', [BtnController::class, 'update']);
    Route::post('/botoes/delete/{id}', [BtnController::class, 'destroy']);

    // Destaques
    Route::post('/destaques', [DestaqueController::class, 'store']);
    Route::post('/destaques/update/{id}', [DestaqueController::class, 'update']);
    Route::post('/destaques/delete/{id}', [DestaqueController::class, 'destroy']);

    // Categorias
    Route::post('/categorias', [CategoriaController::class, 'store']);
    Route::post('/categorias/update/{id}', [CategoriaController::class, 'update']);
    Route::post('/categorias/delete/{id}', [CategoriaController::class, 'destroy']);

    // Notícias
    Route::post('/noticias', [NoticiaController::class, 'store']);
    Route::put('/noticias/update/{id}', [NoticiaController::class, 'update']);
    Route::delete('/noticias/delete/{id}', [NoticiaController::class, 'destroy']);

    // Anexos Notícias
    Route::post('/anexos-noticias', [AnexoNoticiaController::class, 'store']);
    Route::post('/anexos-noticias/update/{id}', [AnexoNoticiaController::class, 'update']);
    Route::post('/anexos-noticias/delete/{id}', [AnexoNoticiaController::class, 'destroy']);
    Route::put('/anexos-noticias/update/{id}', [AnexoNoticiaController::class, 'update']);
    Route::delete('/anexos-noticias/delete/{id}', [AnexoNoticiaController::class, 'destroy']);






    // Comunas
    Route::post('/comunas', [ComunaController::class, 'store']);
    Route::post('/comunas/update/{id}', [ComunaController::class, 'update']);
    Route::post('/comunas/delete/{id}', [ComunaController::class, 'destroy']);

    // Bairros
    Route::post('/bairros', [BairroController::class, 'store']);
    Route::post('/bairros/update/{id}', [BairroController::class, 'update']);
    Route::post('/bairros/delete/{id}', [BairroController::class, 'destroy']);

    // Munícipes
    Route::post('/municipes', [MunicipeController::class, 'store']);
    Route::post('/municipes/update/{id}', [MunicipeController::class, 'update']);
    Route::post('/municipes/delete/{id}', [MunicipeController::class, 'destroy']);

    // Tipos Ocorrências
    Route::post('/tipos-ocorrencias', [TipoOcorrenciaController::class, 'store']);
    Route::post('/tipos-ocorrencias/update/{id}', [TipoOcorrenciaController::class, 'update']);
    Route::post('/tipos-ocorrencias/delete/{id}', [TipoOcorrenciaController::class, 'destroy']);

    Route::post('/ocorrencias/update/{id}', [OcorrenciaController::class, 'update']);
    Route::post('/ocorrencias/delete/{id}', [OcorrenciaController::class, 'destroy']);


    Route::post('/anexos-ocorrencias/update/{id}', [OcorrenciaAnexoController::class, 'update']);
    Route::post('/anexos-ocorrencias/delete/{id}', [OcorrenciaAnexoController::class, 'destroy']);

    // Direção
    Route::post('/direccao', [DireccaoController::class, 'store']);
    Route::post('/direccao/update/{id}', [DireccaoController::class, 'update']);
    Route::post('/direccao/delete/{id}', [DireccaoController::class, 'destroy']);

    // Funcionários
    Route::post('/funcionarios', [FuncionarioController::class, 'store']);
    Route::post('/funcionarios/update/{id}', [FuncionarioController::class, 'update']);
    Route::post('/funcionarios/delete/{id}', [FuncionarioController::class, 'destroy']);

    // Tickets
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::post('/tickets/update/{id}', [TicketController::class, 'update']);
    Route::post('/tickets/delete/{id}', [TicketController::class, 'destroy']);


    // Instituições
    Route::post('/instituicao', [IntituicaoController::class, 'store']);
    Route::post('/instituicao/update/{id}', [IntituicaoController::class, 'update']);
    Route::post('/instituicao/delete/{id}', [IntituicaoController::class, 'destroy']);
});


    // Ocorrências
    Route::post('/ocorrencias', [OcorrenciaController::class, 'store']);
      // Anexos Ocorrências
    Route::post('/anexos-ocorrencias', [OcorrenciaAnexoController::class, 'store']);
/// rotas para noticias crudamente editaveis pelo admin ///
//Route::get('/noticias', [NoticiaController::class, 'index']);
//Route::get('/noticias/{id}', [NoticiaController::class, 'show']);
Route::put('/noticias/update/{id}', [NoticiaController::class, 'update']);
Route::delete('/noticias/delete/{id}', [NoticiaController::class, 'destroy']);


