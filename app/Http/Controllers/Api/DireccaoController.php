<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Direccao;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DireccaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $direccaos = Direccao::with('user')->get();
        return response()->json($direccaos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'responsavel_id' => 'required|exists:users,id',
            'telefone' => 'nullable|string',
            'email' => 'nullable|email',
            'imagem'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'slug' => 'nullable|string',
        ]);

        // Gerar slug automaticamente se não informado
    if (empty($validatedData['slug'])) {
        $validatedData['slug'] = Str::slug($validatedData['nome']);
    }

        // Upload da imagem (se enviada)
    if ($request->hasFile('imagem')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->imagem->getClientOriginalExtension();
        $request->imagem->move(public_path('img/direccaos'), $imageName);
        $validatedData['imagem'] = 'img/direccaos/' . $imageName;
    }

    //  Criar página no banco
    $direccao = Direccao::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'DIreccao criada com sucesso!',
            'data'    => $direccao
        ], 201);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $direccao = Direccao::with('user')->findOrFail($id);
        return response()->json($direccao);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $direccao = Direccao::findOrFail($id);

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'responsavel_id' => 'required|exists:users,id',
            'telefone' => 'nullable|string',
            'email' => 'nullable|email',
            'imagem'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'slug' => 'nullable|string',
        ]);

        // Gerar slug se não informado
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['nome']);
        }

        // Upload de nova imagem (se enviada)
        if ($request->hasFile('imagem')) {
            $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $request->imagem->getClientOriginalExtension();
            $request->imagem->move(public_path('img/direccaos'), $imageName);
            $validatedData['imagem'] = 'img/direccaos/' . $imageName;
        }

        // Atualizar dados
        $direccao->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'direccao atualizada com sucesso!',
                'data'    => $direccao
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $direccao = Direccao::findOrFail($id);
        $direccao->delete();

        return response()->json(['message' => 'Direccao apagada com sucesso']);
    }
}

