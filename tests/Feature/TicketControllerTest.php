<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Ticket;
use App\Models\Ocorrencia;
use App\Models\Direccao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_tickets()
    {
        Ticket::factory()->count(3)->create();

        $response = $this->getJson('/api/tickets');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_show_ticket()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->getJson("/api/tickets/{$ticket->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'status', 'observacoes'
            ]);
    }

    public function test_can_create_ticket()
    {
        $ocorrencia = Ocorrencia::factory()->create();
        $direccao = Direccao::factory()->create();
        $user = User::factory()->create();

        $response = $this->postJson('/api/tickets', [
            'ocorrencia_id' => $ocorrencia->id,
            'direccao_id' => $direccao->id,
            'responsavel_id' => $user->id,
            'status' => 'aberto',
            'observacoes' => 'Ticket de teste'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'status']
            ]);
    }

    public function test_authenticated_user_can_update_ticket()
    {
        $user = $this->createAuthenticatedUser('admin');
        $ticket = Ticket::factory()->create();
        $ocorrencia = Ocorrencia::factory()->create();
        $direccao = Direccao::factory()->create();
        $responsavel = User::factory()->create();

        $response = $this->postJson("/api/tickets/update/{$ticket->id}", [
            'ocorrencia_id' => $ocorrencia->id,
            'direccao_id' => $direccao->id,
            'responsavel_id' => $responsavel->id,
            'status' => 'fechado',
            'observacoes' => 'Ticket atualizado'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'ticket atualizada com sucesso!'
            ]);
    }
}
