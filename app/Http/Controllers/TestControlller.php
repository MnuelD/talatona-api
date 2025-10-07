<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagina;

class TestControlller extends Controller
{
    //
    public function index()
    {

        return view('test.index'); // Exemplo de retorno de view
    }

    public function paginas()
    {
        $paginas = Pagina::all();
        return view('test.paginas', compact('paginas')); // Passa as páginas para
    }

    public function storePagina(Request $request)
    {
        $data = $this->validateData($request);

        // Gerar slug automaticamente se não for enviado
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['titulo']);
        }

        $pagina = Pagina::create($data);

        return redirect()->route('paginas.index')
                         ->with('success', 'Página criada com sucesso!');
    }

   private function validateData(Request $request)
    {
        return $request->validate([
            'titulo' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:paginas,slug',
            'descricao' => 'nullable|string',
            'conteudo' => 'nullable|string',
            'imagem' => 'nullable|string',
            'status' => 'nullable|boolean',
            'ultima_visualizacao' => 'nullable|date',
            'visualizacoes' => 'nullable|integer|min:0',
            'meta_titulo' => 'nullable|string|max:255',
            'meta_descricao' => 'nullable|string|max:255',
        ]);
    }
}
