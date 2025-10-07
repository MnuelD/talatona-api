<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Ocorrencia;
use App\Models\TipoOcorrencia;
use App\Models\Bairro;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OcorrenciaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_ocorrencias()
    {
        Ocorrencia::factory()->count(3)->create();

        $response = $this->getJson('/api/ocorrencias');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_show_ocorrencia()
    {
        $ocorrencia = Ocorrencia::factory()->create();

        $response = $this->getJson("/api/ocorrencias/{$ocorrencia->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'codigo_ocorrencia', 'descricao'
            ]);
    }

    public function test_can_create_ocorrencia()
    {
        $tipoOcorrencia = TipoOcorrencia::factory()->create();
        $bairro = Bairro::factory()->create();

        $response = $this->postJson('/api/ocorrencias', [
            'anonimo' => 'false',
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '923456789',
            'bairro_id' => $bairro->id,
            'tipoOcorrencia_id' => $tipoOcorrencia->id,
            'descricao' => 'Descrição da ocorrência'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'codigo_ocorrencia']
            ]);
    }

    public function test_can_get_ocorrencia_by_codigo()
    {
        $ocorrencia = Ocorrencia::factory()->create();

        $response = $this->getJson("/api/ocorrencias/codigo/{$ocorrencia->codigo_ocorrencia}");

        $response->assertStatus(200)
            ->assertJson([
                'codigo_ocorrencia' => $ocorrencia->codigo_ocorrencia
            ]);
    }
}
