<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoOcorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TipoOcorrenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tipoOcorrencias = TipoOcorrencia::with('ocorrencias')->get();
        return response()->json($tipoOcorrencias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:tipo_ocorrencias',
            'descricao' => 'nullable|string|max:500',
        ]);

        $tipoOcorrencias = TipoOcorrencia::create([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao']
        ]);

        return response()->json($tipoOcorrencias, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $tipoOcorrencias = TipoOcorrencia::with('ocorrencias')->findOrFail($id);
        return response()->json($tipoOcorrencias);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $tipoOcorrencias = TipoOcorrencia::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255|unique:tipo_ocorrencias,nome,' . $tipoOcorrencias->id,
            'descricao' => 'sometimes|nullable|string|max:500',
        ]);

        $tipoOcorrencias->update($validated);

        return response()->json($tipoOcorrencias);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $tipoOcorrencias = TipoOcorrencia::findOrFail($id);
        $tipoOcorrencias->delete();

        return response()->json(['message' => 'Tipo de ocorrência deletado com sucesso.'], 204);
    }
}
