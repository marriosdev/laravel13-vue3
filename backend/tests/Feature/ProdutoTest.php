<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    public function test_deve_listar_produtos_paginados(): void
    {
        Produto::factory()->count(15)->create();

        $response = $this->getJson('/api/produtos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'nome', 'preco_venda', 'estoque', 'custo_medio'],
                ],
                'total',
                'per_page',
                'current_page',
                'last_page',
            ]);

        $this->assertEquals(15, $response->json('total'));
        $this->assertCount(10, $response->json('data'));
    }

    public function test_deve_cadastrar_produto_novo(): void
    {
        $payload = [
            'nome' => 'Fone Ninja X',
            'preco_venda' => 199.90,
        ];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'nome' => 'Fone Ninja X',
                'preco_venda' => 199.9,
                'estoque' => 0,
                'custo_medio' => 0,
            ]);

        $this->assertDatabaseHas('produtos', [
            'nome' => 'Fone Ninja X',
            'preco_venda' => 19990,
            'estoque' => 0,
            'custo_medio' => 0,
        ]);
    }

    public function test_nao_deve_cadastrar_produto_com_nome_curto(): void
    {
        $payload = [
            'nome' => 'ab',
            'preco_venda' => 199.90,
        ];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nome']);
    }

    public function test_nao_deve_cadastrar_produto_com_estoque(): void
    {
        $payload = [
            'nome' => 'Fone Ninja Y',
            'preco_venda' => 100.0,
            'estoque' => 50,
        ];

        $response = $this->postJson('/api/produtos', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('produtos', [
            'nome' => 'Fone Ninja Y',
            'estoque' => 0,
        ]);
    }

    public function test_deve_excluir_produto_via_soft_delete(): void
    {
        $produto = Produto::factory()->create();

        $response = $this->deleteJson('/api/produtos/'.$produto->id);

        $response->assertStatus(204);

        $this->assertSoftDeleted('produtos', [
            'id' => $produto->id,
        ]);
    }

    public function test_nao_deve_excluir_produto_inexistente(): void
    {
        $response = $this->deleteJson('/api/produtos/999999');

        $response->assertStatus(404);
    }
}
