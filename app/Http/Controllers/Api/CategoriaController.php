<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    //
    // Listar todas as categorias
    public function index()
    {
        $categorias = Categoria::with('noticias')->get();
        return response()->json($categorias);
    }

    // Criar nova categoria
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias',
        ]);

        $categoria = Categoria::create([
            'nome' => $validated['nome'],
            'slug' => Str::slug($validated['nome']),
        ]);

        return response()->json($categoria, 201);
    }

    // Mostrar uma categoria
    public function show($id)
    {
        $categoria = Categoria::with('noticias')->findOrFail($id);
        return response()->json($categoria);
    }

    // Atualizar categoria
    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:categorias,nome,' . $categoria->id,
        ]);

        $categoria->update([
            'nome' => $validated['nome'],
            'slug' => Str::slug($validated['nome']),
        ]);

        return response()->json($categoria);
    }

    // Apagar categoria
    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return response()->json(['message' => 'Categoria apagada com sucesso']);
    }
}
