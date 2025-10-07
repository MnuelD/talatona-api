<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoticiaController extends Controller
{
    //
    // Listar todas as noticias
    public function index()
    {
    $noticias = Noticia::with('categoria')->get();

    // Adicionar URL completa para a imagem
    $noticias->transform(function ($noticia) {
        if ($noticia->imagem) {
            $noticia->imagem = secure_asset($noticia->imagem);
 // transforma em URL completa
        }
        return $noticia;
    });

    return response()->json($noticias);
    }

    // Criar nova notícia
    public function store(Request $request)
    {
       $validatedData = $request->validate([
    'titulo' => 'required|string|max:255',
    'descricao' => 'required|string',
    'categoria_id' => 'required|exists:categorias,id',
    'link' => 'nullable|string',
    'midia' => 'nullable|mimes:jpeg,png,jpg,webp,mp4,avi,mov,mkv,webm|max:102400',
    'status' => 'nullable|string',
    'fonte' => 'nullable|string',
    'slug' => 'nullable|string',
    'meta_titulo' => 'nullable|string',
    'meta_descricao' => 'nullable|string',
    'meta_keywords' => 'nullable|string',
    'meta_imagem' => 'nullable|string',
]);

        $validatedData['meta_titulo'] = $validatedData['meta_titulo'] ?? $validatedData['titulo'];
        $validatedData['meta_descricao'] = $validatedData['meta_descricao'] ?? Str::limit($validatedData['descricao'], 160);
        $validatedData['meta_keywords'] = $validatedData['meta_keywords'] ?? implode(',', explode(' ', $validatedData['titulo']));


        // Gerar slug automaticamente se não informado
    if (empty($validatedData['slug'])) {
        $validatedData['slug'] = Str::slug($validatedData['titulo']);
    }

        // Upload da imagem (se enviada)
    if ($request->hasFile('imagem')) {
        $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                   . '.' . $request->imagem->getClientOriginalExtension();
        $request->imagem->move(public_path('img/noticias'), $imageName);
        $validatedData['imagem'] = 'img/noticias/' . $imageName;
    }

    //  Criar página no banco
    $noticia = Noticia::create($validatedData);

    // Resposta diferenciada para Web e API
    if ($request->wantsJson()) {
        // 🔹 API: Retorno JSON
        return response()->json([
            'message' => 'Noticia criada com sucesso!',
            'data'    => $noticia
        ], 201);
    }


    }

    // Mostrar uma notícia
public function show($id)
{
    $noticia = Noticia::with('categoria')->findOrFail($id);

    // Adicionar URL completa para a imagem
    if ($noticia->imagem) {
        $noticia->imagem = asset($noticia->imagem); // transforma em URL completa
    }

    return response()->json($noticia);
}



    // Atualizar notícia
    public function update(Request $request, $id)
    {
        $noticia = Noticia::findOrFail($id);

        $validatedData = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'link' => 'nullable|string',
            'imagem'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'nullable|string',
            'fonte' => 'nullable|string',
            'slug' => 'nullable|string',
            'meta_titulo' => 'nullable|string',
            'meta_descricao' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_imagem' => 'nullable|string',
        ]);

        // Gerar slug se não informado
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['titulo']);
        }

        // Upload de nova imagem (se enviada)
        if ($request->hasFile('imagem')) {
            $imageName = time() . '_' . Str::slug(pathinfo($request->imagem->getClientOriginalName(), PATHINFO_FILENAME))
                       . '.' . $request->imagem->getClientOriginalExtension();
            $request->imagem->move(public_path('img/noticias'), $imageName);
            $validatedData['imagem'] = 'img/noticias/' . $imageName;
        }

        // Atualizar dados
        $noticia->update($validatedData);

        if ($request->wantsJson()) {
            // 🔹 API: Retorno JSON
            return response()->json([
                'message' => 'noticia atualizada com sucesso!',
                'data'    => $noticia
            ], 200);
        }
    }

    // Apagar notícia
    public function destroy($id)
    {
        $noticia = Noticia::findOrFail($id);
        $noticia->delete();

        return response()->json(['message' => 'Notícia apagada com sucesso']);
    }
}
