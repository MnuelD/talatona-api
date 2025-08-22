<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnexoOcorrencia; // Assuming this is the model for OcorrenciaAnexo
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OcorrenciaAnexoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Listar todos os anexos de ocorrências
        $anexoOcorrencia = AnexoOcorrencia::all();
        return response()->json($anexoOcorrencia);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos
    $validatedData = $request->validate([
        'ocorrencia_id' => 'required|exists:noticias,id',
        'anexo'      => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpeg,png,jpg,webp|max:2048', // Limite de 2MB
        'descricao'  => 'nullable|string|max:255', // Descrição opcional
        'meta_keywords' => 'nullable|string|max:255', // Meta keywords opcionais
    ]);

    // Upload da imagem (se enviada)
    if ($request->hasFile('anexo')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->imagem->getClientOriginalExtension();
        $request->imagem->move(public_path('img/ocorrencias'), $imageName);
        $validatedData['anexo'] = 'img/ocorrencias/' . $imageName;
    }

    //  Criar página no banco
    $anexoOcorrencia = AnexoOcorrencia::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'anexo ocorrencia criada com sucesso!',
            'data'    => $anexoOcorrencia
        ], 201);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $anexoOcorrencia = AnexoOcorrencia::findOrFail($id);
         // Sempre retorna JSON
    return response()->json([
        'message' => 'Anexo ocorrencia encontrada com sucesso!',
        'data'    => $anexoOcorrencia
    ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $anexoOcorrencia = AnexoOcorrencia::findOrFail($id);


        // Validação
        // Validação dos dados recebidos
    $validatedData = $request->validate([
        'ocorrencia_id' => 'required|exists:noticias,id',
        'anexo'      => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpeg,png,jpg,webp|max:2048', // Limite de 2MB
        'descricao'  => 'nullable|string|max:255', // Descrição opcional
        'meta_keywords' => 'nullable|string|max:255', // Meta keywords opcionais
    ]);


    // Upload da imagem (se enviada)
    if ($request->hasFile('anexo')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->imagem->getClientOriginalExtension();
        $request->imagem->move(public_path('img/ocorrencias'), $imageName);
        $validatedData['anexo'] = 'img/ocorrencias/' . $imageName;
    }

        // Atualizar dados
        $anexoOcorrencia->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'Anexos ocorrencia  actualizada com sucesso!',
                'data'    => $anexoOcorrencia
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
         $anexoOcorrencia = AnexoOcorrencia::findOrFail($id);

    // Deletar a página
    $anexoOcorrencia->delete();

    return response()->json([
        'message' => 'Anexo da ocorrencia deletado!'
    ], 200);
    }
}
