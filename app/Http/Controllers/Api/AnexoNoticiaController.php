<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnexoNoticia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AnexoNoticiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anexos = AnexoNoticia::all();
        return response()->json($anexos);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
{
    // Validação dos dados recebidos
    $validatedData = $request->validate([
        'noticia_id' => 'required|exists:noticias,id',
        'anexo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // máx. 2MB
        'descricao'  => 'nullable|string|max:255', // Descrição opcional
        'meta_keywords' => 'nullable|string|max:255', // Meta keywords opcionais
    ]);


    // Upload da imagem (se enviada)
    if ($request->hasFile('anexo')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->anexo->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->anexo->getClientOriginalExtension();
        $request->anexo->move(public_path('img/noticias'), $imageName);
        $validatedData['anexo'] = 'img/noticias/' . $imageName;
    }

    //  Criar página no banco
    $anexo = AnexoNoticia::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'anexo criada com sucesso!',
            'data'    => $anexo
        ], 201);
    }

}

  public function show($id)
{
    $anexo = AnexoNoticia::findOrFail($id);

    // Sempre retorna JSON
    return response()->json([
        'message' => 'Anexo encontrada com sucesso!',
        'data'    => $anexo
    ], 200);
}


    /**
     * Atualizar uma página existente
     */
    public function update(Request $request, $id)
    {
        $anexo = AnexoNoticia::findOrFail($id);

        // Validação
        // Validação dos dados recebidos
    $validatedData = $request->validate([
        'noticia_id' => 'required|exists:noticias,id',
        'anexo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // máx. 2MB
        'descricao'  => 'nullable|string|max:255', // Descrição opcional
        'meta_keywords' => 'nullable|string|max:255', // Meta keywords opcionais
    ]);


   // Upload da imagem (se enviada)
    if ($request->hasFile('anexo')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->anexo->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->anexo->getClientOriginalExtension();
        $request->anexo->move(public_path('img/noticias'), $imageName);
        $validatedData['anexo'] = 'img/noticias/' . $imageName;
    }

        // Atualizar dados
        $anexo->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'Anexos  atualizada com sucesso!',
                'data'    => $anexo
            ], 200);
        }


    }

    /**
     * Remover uma página
     */
   public function destroy(Request $request, $id)
{
    $anexo = AnexoNoticia::findOrFail($id);

    // Deletar a página
    $anexo->delete();

    return response()->json([
        'message' => 'Anexo da noticia deletado!'
    ], 200);
}

}

