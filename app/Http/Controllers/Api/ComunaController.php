<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comuna;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $comunas = Comuna::with('bairros')->get();
        return response()->json($comunas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:comunas',
            'descricao' => 'nullable|string|max:500',
        ]);

        $comunas = Comuna::create([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        return response()->json($comunas, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $comuna = Comuna::with('bairros')->findOrFail($id);
        return response()->json($comuna);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $comuna = Comuna::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:comunas,nome,' . $comuna->id,
            'descricao' => 'nullable|string|max:500',
        ]);

        $comuna->update([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        return response()->json($comuna);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $comuna = Comuna::findOrFail($id);
        $comuna->delete();

        return response()->json(['message' => 'Comuna apagada com sucesso']);
    }
}
