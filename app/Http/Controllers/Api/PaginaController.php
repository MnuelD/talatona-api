<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pagina;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PaginaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginas = Pagina::all();
        return response()->json($paginas);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
{
    // Validação dos dados recebidos
    $validatedData = $request->validate([
        'titulo'          => 'required|string|max:255',
        'slug'            => 'nullable|string|max:255|unique:paginas,slug',
        'descricao'       => 'nullable|string',
        'imagem'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // máx. 2MB
        'estado'          => 'nullable|in:ativo,inativo',
        'meta_keywords'     => 'nullable|string|max:255',
    ]);

    // Gerar slug automaticamente se não informado
    if (empty($validatedData['slug'])) {
        $validatedData['slug'] = Str::slug($validatedData['titulo']);
    }

    // Upload da imagem (se enviada)
    if ($request->hasFile('imagem')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->imagem->getClientOriginalExtension();
        $request->imagem->move(public_path('img/pagina'), $imageName);
        $validatedData['imagem'] = 'img/pagina/' . $imageName;
    }

    //  Criar página no banco
    $pagina = Pagina::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'Página criada com sucesso!',
            'data'    => $pagina
        ], 201);
    }

}

  public function show($id)
{
    $pagina = Pagina::findOrFail($id);

    // Sempre retorna JSON
    return response()->json([
        'message' => 'Página encontrada com sucesso!',
        'data'    => $pagina
    ], 200);
}


    /**
     * Atualizar uma página existente
     */
    public function update(Request $request, $id)
    {
        $pagina = Pagina::findOrFail($id);

        // Validação
        $validatedData = $request->validate([
            'titulo'        => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:paginas,slug,' . $pagina->id,
            'descricao'     => 'nullable|string',
            'imagem'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'estado'        => 'nullable|in:ativo,inativo',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        // Gerar slug se não informado
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['titulo']);
        }

        // Upload de nova imagem (se enviada)
        if ($request->hasFile('imagem')) {
            $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $request->imagem->getClientOriginalExtension();
            $request->imagem->move(public_path('img/pagina'), $imageName);
            $validatedData['imagem'] = 'img/pagina/' . $imageName;
        }

        // Atualizar dados
        $pagina->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'Página atualizada com sucesso!',
                'data'    => $pagina
            ], 200);
        }


    }

    /**
     * Remover uma página
     */
   public function destroy(Request $request, $id)
{
    $pagina = Pagina::findOrFail($id);

    // Deletar botões relacionados
    $pagina->botoes()->delete();

    // Deletar a página
    $pagina->delete();

    return response()->json([
        'message' => 'Página e seus botões excluídos com sucesso!'
    ], 200);
}

}
