<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrarVendaValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_nao_deve_registrar_venda_sem_cliente(): void
    {
        $payload = [
            'produtos' => [
                ['id' => 1, 'quantidade' => 1, 'preco_unitario' => 100.00],
            ],
        ];

        $response = $this->postJson('/api/vendas', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cliente']);
    }

    public function test_nao_deve_registrar_venda_sem_produtos(): void
    {
        $payload = [
            'cliente' => 'Cliente Teste',
        ];

        $response = $this->postJson('/api/vendas', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['produtos']);
    }

    public function test_nao_deve_registrar_venda_com_array_produtos_vazio(): void
    {
        $payload = [
            'cliente' => 'Cliente Teste',
            'produtos' => [],
        ];

        $response = $this->postJson('/api/vendas', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['produtos']);
    }

    public function test_nao_deve_registrar_venda_com_produto_inexistente(): void
    {
        $payload = [
            'cliente' => 'Cliente Teste',
            'produtos' => [
                ['id' => 9999, 'quantidade' => 1, 'preco_unitario' => 100.00],
            ],
        ];

        $response = $this->postJson('/api/vendas', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['produtos.0.id']);
    }

    public function test_nao_deve_registrar_venda_com_quantidade_invalida(): void
    {
        $produto = Produto::factory()->create();

        $payload = [
            'cliente' => 'Cliente Teste',
            'produtos' => [
                ['id' => $produto->id, 'quantidade' => 0, 'preco_unitario' => 100.00],
                ['id' => $produto->id, 'quantidade' => -2, 'preco_unitario' => 100.00],
            ],
        ];

        $response = $this->postJson('/api/vendas', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'produtos.0.quantidade',
                'produtos.1.quantidade',
            ]);
    }

    public function test_nao_deve_registrar_venda_com_preco_invalido(): void
    {
        $produto = Produto::factory()->create();

        $payload = [
            'cliente' => 'Cliente Teste',
            'produtos' => [
                ['id' => $produto->id, 'quantidade' => 1, 'preco_unitario' => 'abc'],
                ['id' => $produto->id, 'quantidade' => 1, 'preco_unitario' => -50.00],
            ],
        ];

        $response = $this->postJson('/api/vendas', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'produtos.0.preco_unitario',
                'produtos.1.preco_unitario',
            ]);
    }
}
