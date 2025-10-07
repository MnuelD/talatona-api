<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $funcionarios = Funcionario::with('user')->with('direccao')->get();
        return response()->json($funcionarios);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'descricao' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'direccao_id' => 'required|exists:direccaos,id',
            'slug' => 'nullable|string',
        ]);

        // Gerar slug automaticamente se não informado
    if (empty($validatedData['slug'])) {
        $validatedData['slug'] = Str::slug($validatedData['descricao']);
    }


    //  Criar página no banco
    $funcionario = Funcionario::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'Funcionario criada com sucesso!',
            'data'    => $funcionario
        ], 201);
    }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $funcionario = Funcionario::with('user')->findOrFail($id);
        return response()->json($funcionario);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $funcionario = Funcionario::findOrFail($id);

        $validatedData = $request->validate([
            'descricao' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'direccao_id' => 'required|exists:direccaos,id',
            'slug' => 'nullable|string',
        ]);

        // Gerar slug se não informado
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['descricao']);
        }



        // Atualizar dados
        $funcionario->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'Funcionario atualizada com sucesso!',
                'data'    => $funcionario
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $funcionario = Funcionario::findOrFail($id);
        $funcionario->delete();

        return response()->json(['message' => 'funcionario apagada com sucesso']);
    }
}
