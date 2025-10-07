<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instituicao;
use Illuminate\Support\Str;
class IntituicaoController extends Controller
{
    //
    public function index()
    {
        //
        $instituicaos = Instituicao::with('responsavel')->with('localizacao')->get();
        // Adicionar URL completa para a imagem
    $instituicaos->transform(function ($instituicao) {
        if ($instituicao->imagem) {
            $instituicao->imagem = secure_asset($instituicao->imagem);
        // transforma em URL completa
        }
        return $instituicao;
    });
        return response()->json($instituicaos);
    }

    public function show($slug)
    {
        //
        $instituicao = Instituicao::where('slug', $slug)->with('responsavel')->with('localizacao')->first();
        // Adicionar URL completa para a imagem
    if ($instituicao->imagem) {
        $instituicao->imagem = asset($instituicao->imagem); // transforma em URL completa
    }

    return response()->json($instituicao);
    }



    public function store(Request $request)
    {
        //
       $validatedData = $request->validate([
            'nome' => 'required|string|max:255|unique:instituicaos',
            'slug' => 'required|string|max:255|unique:instituicaos',
            'telefone' => 'required|string|max:20',
            'responsavel' => 'required|exists:users,id',
            'localizacao' => 'required|exists:bairros,id',
            'categoria' => 'required|in:hospital,escola,esquadra,empresa,mercado,instituicao',
            'tipo_instituicao' => 'required|in:publica,privada',
            'ponto_referencia' => 'nullable|string|max:255',
            'imagem'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'descricao' => 'nullable|string',
        ]);

        // Gerar slug automaticamente se não informado
    if (empty($validatedData['slug'])) {
        $validatedData['slug'] = Str::slug($validatedData['nome']);
    }

    if ($request->hasFile('imagem')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->imagem->getClientOriginalExtension();
        $request->imagem->move(public_path('img/instituicao'), $imageName);
        $validatedData['imagem'] = 'img/instituicao/' . $imageName;
    }

        $instituicao = Instituicao::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'Instituicao  criada com sucesso!',
            'data'    => $instituicao
        ], 201);
    }
    }

    public function update(Request $request, $id)
{
    // Buscar a instituição existente
    $instituicao = Instituicao::findOrFail($id);

    // Validação dos dados (permitindo manter o mesmo nome/slug da própria instituição)
    $validatedData = $request->validate([
        'nome' => 'required|string|max:255|unique:instituicaos,nome,' . $instituicao->id,
        'slug' => 'required|string|max:255|unique:instituicaos,slug,' . $instituicao->id,
        'telefone' => 'required|string|max:20',
        'responsavel' => 'required|exists:users,id',
        'localizacao' => 'required|exists:bairros,id',
        'categoria' => 'required|in:hospital,escola,esquadra,empresa,mercado,instituicao',
        'tipo_instituicao' => 'required|in:publica,privada',
        'ponto_referencia' => 'nullable|string|max:255',
        'imagem' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'descricao' => 'nullable|string',
    ]);

    // Gerar slug automaticamente se não informado
    if (empty($validatedData['slug'])) {
        $validatedData['slug'] = Str::slug($validatedData['nome']);
    }

    // Substituir imagem se enviada
    if ($request->hasFile('imagem')) {
        // Apagar imagem antiga (se existir)
        if (!empty($instituicao->imagem) && file_exists(public_path($instituicao->imagem))) {
            unlink(public_path($instituicao->imagem));
        }

        // Guardar nova imagem
        $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->imagem->getClientOriginalExtension();

        $request->imagem->move(public_path('img/instituicao'), $imageName);
        $validatedData['imagem'] = 'img/instituicao/' . $imageName;
    }

    // Atualizar os dados
    $instituicao->update($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        return response()->json([
            'message' => 'Instituição atualizada com sucesso!',
            'data'    => $instituicao
        ], 200);
    }

    // Opcional: redirecionar se for requisição web
    return redirect()->back()->with('success', 'Instituição atualizada com sucesso!');
}


    // Apagar notícia
    public function destroy($id)
    {
        $instituicao = Instituicao::findOrFail($id);
        $instituicao->delete();

        return response()->json(['message' => 'Instituição apagada com sucesso']);
    }



}





