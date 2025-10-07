<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Destaque;
use App\Models\Pagina;

class DestaqueController extends Controller
{
    /**
     * Listar todos os destaques
     */
    public function index()
    {
        $destaques = Destaque::all();
        return response()->json([
            'message' => 'Lista de destaques',
            'data' => $destaques
        ], 200);
    }

    /**
     * Criar um novo destaque
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo'     => 'required|string|max:255',
            'descricao'  => 'nullable|string',
            'icone'      => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'link_text'  => 'nullable|string|max:255',
            'link'       => 'nullable|string|max:255',
            'pagina'     => 'required|exists:paginas,id',
        ]);

        // Upload do ícone
        if ($request->hasFile('icone')) {
            $imageName = time() . '_' . Str::slug(pathinfo($request->icone->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $request->icone->getClientOriginalExtension();
            $request->icone->move(public_path('img/icones'), $imageName);
            $validatedData['icone'] = 'img/icones/' . $imageName;
        }

        $destaque = Destaque::create($validatedData);

        return response()->json([
            'message' => 'Destaque criado com sucesso!',
            'data'    => $destaque
        ], 201);
    }

    /**
     * Exibir um destaque específico
     */
    public function show($id)
    {
        $destaque = Destaque::findOrFail($id);

        return response()->json([
            'message' => 'Destaque encontrado!',
            'data'    => $destaque
        ], 200);
    }

    /**
     * Atualizar um destaque existente
     */
    public function update(Request $request, $id)
    {
        $destaque = Destaque::findOrFail($id);

        $validatedData = $request->validate([
            'titulo'     => 'required|string|max:255',
            'descricao'  => 'nullable|string',
            'icone'      => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'link_text'  => 'nullable|string|max:255',
            'link'       => 'nullable|string|max:255',
            'pagina'     => 'required|exists:paginas,id',
        ]);

        // Upload do ícone, se enviado
        if ($request->hasFile('icone')) {
            $imageName = time() . '_' . Str::slug(pathinfo($request->icone->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $request->icone->getClientOriginalExtension();
            $request->icone->move(public_path('img/icones'), $imageName);
            $validatedData['icone'] = 'img/icones/' . $imageName;
        }

        $destaque->update($validatedData);

        return response()->json([
            'message' => 'Destaque atualizado com sucesso!',
            'data'    => $destaque
        ], 200);
    }

    /**
     * Deletar um destaque
     */
    public function destroy($id)
    {
        $destaque = Destaque::findOrFail($id);
        $destaque->delete();

        return response()->json([
            'message' => 'Destaque deletado com sucesso!'
        ], 200);
    }
}
