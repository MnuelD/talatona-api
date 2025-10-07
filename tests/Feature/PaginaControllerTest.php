<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pagina;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PaginaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_paginas()
    {
        Pagina::factory()->count(3)->create();

        $response = $this->getJson('/api/paginas');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_show_pagina()
    {
        $pagina = Pagina::factory()->create();

        $response = $this->getJson("/api/paginas/{$pagina->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $pagina->id,
                    'titulo' => $pagina->titulo
                ]
            ]);
    }

    public function test_authenticated_user_can_create_pagina()
    {
        $user = $this->createAuthenticatedUser('admin');

        Storage::fake('public');

        $response = $this->postJson('/api/paginas', [
            'titulo' => 'Nova Página',
            'descricao' => 'Descrição da página',
            'estado' => 'ativo',
            'meta_keywords' => 'teste, pagina',
            'imagem' => UploadedFile::fake()->image('pagina.jpg')
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'titulo', 'slug']
            ]);
    }

    public function test_authenticated_user_can_update_pagina()
    {
        $user = $this->createAuthenticatedUser('admin');
        $pagina = Pagina::factory()->create();

        $response = $this->putJson("/api/paginas/update/{$pagina->id}", [
            'titulo' => 'Título Atualizado',
            'descricao' => 'Descrição atualizada',
            'estado' => 'inativo'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Página atualizada com sucesso!'
            ]);
    }

    public function test_authenticated_user_can_delete_pagina()
    {
        $user = $this->createAuthenticatedUser('admin');
        $pagina = Pagina::factory()->create();

        $response = $this->deleteJson("/api/paginas/delete/{$pagina->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Página e seus botões excluídos com sucesso!'
            ]);

        $this->assertDatabaseMissing('paginas', ['id' => $pagina->id]);
    }
}
