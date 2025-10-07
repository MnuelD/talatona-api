<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Noticia;
use App\Models\Categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class NoticiaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_noticias()
    {
        Noticia::factory()->count(3)->create();

        $response = $this->getJson('/api/noticias');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_show_noticia()
    {
        $noticia = Noticia::factory()->create();

        $response = $this->getJson("/api/noticias/{$noticia->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'titulo', 'descricao'
            ]);
    }

    public function test_authenticated_user_can_create_noticia()
    {
        $user = $this->createAuthenticatedUser('admin');
        $categoria = Categoria::factory()->create();

        $response = $this->postJson('/api/noticias', [
            'titulo' => 'Nova Notícia',
            'descricao' => 'Descrição da notícia',
            'categoria_id' => $categoria->id,
            'status' => 'publicada',
            'fonte' => 'Teste'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'titulo', 'slug']
            ]);
    }

    public function test_authenticated_user_can_update_noticia()
    {
        $user = $this->createAuthenticatedUser('admin');
        $noticia = Noticia::factory()->create();
        $categoria = Categoria::factory()->create();

        $response = $this->putJson("/api/noticias/update/{$noticia->id}", [
            'titulo' => 'Notícia Atualizada',
            'descricao' => 'Descrição atualizada',
            'categoria_id' => $categoria->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'noticia atualizada com sucesso!'
            ]);
    }

    public function test_authenticated_user_can_delete_noticia()
    {
        $user = $this->createAuthenticatedUser('admin');
        $noticia = Noticia::factory()->create();

        $response = $this->deleteJson("/api/noticias/delete/{$noticia->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Notícia apagada com sucesso'
            ]);
    }
}
