<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Municipe;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MunicipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $municipes = Municipe::with('bairro')->with('user')->get();
        return response()->json($municipes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'bairro_id' => 'required|exists:bairros,id',
            'funcao' => 'nullable|string',
        ]);



    //  Criar página no banco
    $municipe = Municipe::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'Municipe criado com sucesso!',
            'data'    => $municipe
        ], 201);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $municipe = Municipio::with('bairro')->with('user')->findOrFail($id);
        return response()->json($municipe);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $municipe = Municipe::findOrFail($id);

        $validatedData = $request->validate([
           'user_id' => 'required|exists:users,id',
            'bairro_id' => 'required|exists:bairros,id',
            'funcao' => 'nullable|string',
        ]);



        // Atualizar dados
        $municipe->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'muncipe atualizada com sucesso!',
                'data'    => $municipe
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     **/
    public function destroy(string $id)
    {
        //
        $municipe = Municipe::findOrFail($id);
        $municipe->delete();

        return response()->json(['message' => 'Municipe apagada com sucesso']);
    }
}
