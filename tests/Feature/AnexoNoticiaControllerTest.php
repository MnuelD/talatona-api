<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AnexoNoticia;
use App\Models\Noticia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class AnexoNoticiaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_anexos_noticias()
    {
        AnexoNoticia::factory()->count(3)->create();

        $response = $this->getJson('/api/anexos-noticias');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_show_anexo_noticia()
    {
        $anexo = AnexoNoticia::factory()->create();

        $response = $this->getJson("/api/anexos-noticias/{$anexo->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'noticia_id']
            ]);
    }

    public function test_can_get_anexos_by_noticia()
    {
        $noticia = Noticia::factory()->create();
        AnexoNoticia::factory()->count(2)->create(['noticia_id' => $noticia->id]);

        $response = $this->getJson("/api/anexos-noticias/by-noticia/{$noticia->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_authenticated_user_can_create_anexo_noticia()
    {
        $user = $this->createAuthenticatedUser('admin');
        $noticia = Noticia::factory()->create();

        $response = $this->postJson('/api/anexos-noticias', [
            'noticia_id' => $noticia->id,
            'descricao' => 'Anexo de teste'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'noticia_id']
            ]);
    }
}
