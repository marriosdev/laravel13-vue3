<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrarCompraValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_deve_registrar_compra_sem_fornecedor(): void
    {
        $payload = [
            'produtos' => [
                ['id' => 1, 'quantidade' => 10, 'preco_unitario' => 50.00],
            ],
        ];

        $response = $this->postJson('/api/compras', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['fornecedor']);
    }

    public function test_nao_deve_registrar_compra_sem_produtos(): void
    {
        $payload = [
            'fornecedor' => 'Ninja Fornecedor',
        ];

        $response = $this->postJson('/api/compras', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['produtos']);
    }

    public function test_nao_deve_registrar_compra_com_array_produtos_vazio(): void
    {
        $payload = [
            'fornecedor' => 'Ninja Fornecedor',
            'produtos' => [],
        ];

        $response = $this->postJson('/api/compras', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['produtos']);
    }

    public function test_nao_deve_registrar_compra_com_produto_inexistente(): void
    {
        $payload = [
            'fornecedor' => 'Ninja Fornecedor',
            'produtos' => [
                ['id' => 9999, 'quantidade' => 10, 'preco_unitario' => 50.00],
            ],
        ];

        $response = $this->postJson('/api/compras', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['produtos.0.id']);
    }

    public function test_nao_deve_registrar_compra_com_quantidade_invalida(): void
    {
        $produto = Produto::factory()->create();

        $payload = [
            'fornecedor' => 'Ninja Fornecedor',
            'produtos' => [
                ['id' => $produto->id, 'quantidade' => 0, 'preco_unitario' => 50.00],
                ['id' => $produto->id, 'quantidade' => -5, 'preco_unitario' => 50.00],
            ],
        ];

        $response = $this->postJson('/api/compras', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'produtos.0.quantidade',
                'produtos.1.quantidade',
            ]);
    }

    public function test_nao_deve_registrar_compra_com_preco_invalido(): void
    {
        $produto = Produto::factory()->create();

        $payload = [
            'fornecedor' => 'Ninja Fornecedor',
            'produtos' => [
                ['id' => $produto->id, 'quantidade' => 10, 'preco_unitario' => 'abc'],
                ['id' => $produto->id, 'quantidade' => 10, 'preco_unitario' => -10.00],
            ],
        ];

        $response = $this->postJson('/api/compras', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'produtos.0.preco_unitario',
                'produtos.1.preco_unitario',
            ]);
    }
}
