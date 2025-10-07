<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bairro;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BairroController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $bairros = Bairro::with('comuna')->get();
        return response()->json($bairros);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'comuna_id' => 'required|exists:comunas,id',
            'nome' => 'nullable|string',
            'slug' => 'nullable|string',
            'ponto_referencia' => 'nullable|string',
            'imagem'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'descricao' => 'nullable|string',
        ]);
        // Gerar slug automaticamente se não informado
    if (empty($validatedData['slug'])) {
        $validatedData['slug'] = Str::slug($validatedData['nome']);
    }

        // Upload da imagem (se enviada)
    if ($request->hasFile('imagem')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->imagem->getClientOriginalExtension();
        $request->imagem->move(public_path('img/bairros'), $imageName);
        $validatedData['imagem'] = 'img/bairros/' . $imageName;
    }

    //  Criar página no banco
    $bairro = Bairro::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'Bairro criada com sucesso!',
            'data'    => $bairro
        ], 201);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $bairro = Bairro::with('comuna')->findOrFail($id);
        return response()->json($bairro);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $bairro = Bairro::findOrFail($id);

        $validatedData = $request->validate([
           'comuna_id' => 'required|exists:comunas,id',
            'nome' => 'nullable|string',
            'slug' => 'nullable|string',
            'ponto_referencia' => 'nullable|string',
            'imagem'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'descricao' => 'nullable|string',
        ]);

        // Gerar slug se não informado
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['nome']);
        }

        // Upload de nova imagem (se enviada)
        if ($request->hasFile('imagem')) {
            $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $request->imagem->getClientOriginalExtension();
            $request->imagem->move(public_path('img/bairros'), $imageName);
            $validatedData['imagem'] = 'img/bairros/' . $imageName;
        }

        // Atualizar dados
        $bairro->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'bairro atualizada com sucesso!',
                'data'    => $bairro
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $bairro = Bairro::findOrFail($id);
        $bairro->delete();

        return response()->json(['message' => 'Bairro apagada com sucesso']);
    }
}
