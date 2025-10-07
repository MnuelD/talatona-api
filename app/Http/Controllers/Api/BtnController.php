<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BtnPagina;

class BtnController extends Controller
{
    /**
     * Listar todos os botões
     */
    public function index()
    {
        $botoes = BtnPagina::all();
        return response()->json([
            'message' => 'Lista de botões',
            'data' => $botoes
        ], 200);
    }

    /**
     * Criar um novo botão
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'texto'      => 'required|string|max:255',
            'link'       => 'nullable|string|max:255',
            'icone'      => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'target'     => 'nullable|in:_self,_blank',
            'pagina_id'  => 'required|exists:paginas,id',
        ]);

        // Upload da imagem do ícone
        if ($request->hasFile('icone')) {
            $imageName = time() . '_' . Str::slug(pathinfo($request->icone->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $request->icone->getClientOriginalExtension();
            $request->icone->move(public_path('img/icones'), $imageName);
            $validatedData['icone'] = 'img/icones/' . $imageName;
        }

        $btn = BtnPagina::create($validatedData);

        return response()->json([
            'message' => 'Botão criado com sucesso!',
            'data'    => $btn
        ], 201);
    }

    /**
     * Exibir um botão específico
     */
    public function show($id)
    {
        $btn = BtnPagina::findOrFail($id);

        return response()->json([
            'message' => 'Botão encontrado!',
            'data'    => $btn
        ], 200);
    }

    /**
     * Atualizar um botão existente
     */
    public function update(Request $request, $id)
    {
        $btn = BtnPagina::findOrFail($id);

        $validatedData = $request->validate([
            'texto'      => 'required|string|max:255',
            'link'       => 'nullable|string|max:255',
            'icone'      => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'target'     => 'nullable|in:_self,_blank',
            'pagina_id'  => 'required|exists:paginas,id',
        ]);

        // Upload da imagem do ícone, se enviada
        if ($request->hasFile('icone')) {
            $imageName = time() . '_' . Str::slug(pathinfo($request->icone->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $request->icone->getClientOriginalExtension();
            $request->icone->move(public_path('img/icones'), $imageName);
            $validatedData['icone'] = 'img/icones/' . $imageName;
        }

        $btn->update($validatedData);

        return response()->json([
            'message' => 'Botão atualizado com sucesso!',
            'data'    => $btn
        ], 200);
    }

    /**
     * Deletar um botão
     */
    public function destroy($id)
    {
        $btn = BtnPagina::findOrFail($id);
        $btn->delete();

        return response()->json([
            'message' => 'Botão deletado com sucesso!'
        ], 200);
    }
}

