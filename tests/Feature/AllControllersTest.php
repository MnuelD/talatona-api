<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AllControllersTest extends TestCase
{
    use RefreshDatabase;

    // BtnController Tests
    public function test_can_list_botoes()
    {
        $response = $this->getJson('/api/botoes');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_botao()
    {
        $user = $this->createAuthenticatedUser('admin');
        $pagina = \App\Models\Pagina::factory()->create();

        $response = $this->postJson('/api/botoes', [
            'texto' => 'Botão Teste',
            'link' => 'https://example.com',
            'pagina_id' => $pagina->id,
            'target' => '_blank'
        ]);

        $response->assertStatus(201);
    }

    // CategoriaController Tests
    public function test_can_list_categorias()
    {
        \App\Models\Categoria::factory()->count(2)->create();

        $response = $this->getJson('/api/categorias');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_authenticated_user_can_create_categoria()
    {
        $user = $this->createAuthenticatedUser('admin');

        $response = $this->postJson('/api/categorias', [
            'nome' => 'Categoria Teste'
        ]);

        $response->assertStatus(201);
    }

    // DestaqueController Tests
    public function test_can_list_destaques()
    {
        $response = $this->getJson('/api/destaques');
        $response->assertStatus(200);
    }

    // ComunaController Tests
    public function test_can_list_comunas()
    {
        \App\Models\Comuna::factory()->count(2)->create();

        $response = $this->getJson('/api/comunas');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    // BairroController Tests
    public function test_can_list_bairros()
    {
        \App\Models\Bairro::factory()->count(2)->create();

        $response = $this->getJson('/api/bairros');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    // MunicipeController Tests
    public function test_can_list_municipes()
    {
        $response = $this->getJson('/api/municipes');
        $response->assertStatus(200);
    }

    // TipoOcorrenciaController Tests
    public function test_can_list_tipos_ocorrencias()
    {
        \App\Models\TipoOcorrencia::factory()->count(2)->create();

        $response = $this->getJson('/api/tipos-ocorrencias');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    // DireccaoController Tests
    public function test_can_list_direccoes()
    {
        $response = $this->getJson('/api/direccao');
        $response->assertStatus(200);
    }

    // FuncionarioController Tests
    public function test_can_list_funcionarios()
    {
        $response = $this->getJson('/api/funcionarios');
        $response->assertStatus(200);
    }

    // IntituicaoController Tests
    public function test_can_list_instituicoes()
    {
        $response = $this->getJson('/api/instituicao');
        $response->assertStatus(200);
    }

    public function test_can_show_instituicao_by_slug()
    {
        $instituicao = \App\Models\Instituicao::factory()->create();

        $response = $this->getJson("/api/instituicao/{$instituicao->slug}");
        $response->assertStatus(200);
    }

    // OcorrenciaAnexoController Tests
    public function test_can_list_anexos_ocorrencias()
    {
        $response = $this->getJson('/api/anexos-ocorrencias');
        $response->assertStatus(200);
    }

    // Testes de autenticação para rotas protegidas
    public function test_protected_routes_require_authentication()
    {
        // Testar algumas rotas protegidas
        $routes = [
            'post' => ['/api/paginas', '/api/noticias'],
            'put' => ['/api/paginas/update/1'],
            'delete' => ['/api/paginas/delete/1']
        ];

        foreach ($routes as $method => $urls) {
            foreach ($urls as $url) {
                $response = $this->{$method . 'Json'}($url);
                $response->assertStatus(401);
            }
        }
    }
}
