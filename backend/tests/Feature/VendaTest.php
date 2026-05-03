<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_deve_registrar_venda_e_calcular_lucro(): void
    {
        $produto = Produto::factory()->create([
            'estoque' => 10,
            'custo_medio' => 5000, // 50.00
            'preco_venda' => 10000, // 100.00
        ]);

        $payload = [
            'cliente' => 'Cliente Ninja',
            'produtos' => [
                [
                    'id' => $produto->id,
                    'quantidade' => 2,
                    'preco_unitario' => 100.00,
                ],
            ],
        ];

        $response = $this->postJson('/api/vendas', $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'cliente' => 'Cliente Ninja',
                'valor_total' => 200.0,
                'lucro_total' => 100.0, // (100 - 50) * 2
            ]);

        $this->assertDatabaseHas('vendas', [
            'cliente' => 'Cliente Ninja',
            'valor_total' => 20000,
            'lucro_total' => 10000,
        ]);

        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'estoque' => 8, // 10 - 2
        ]);
    }

    public function test_nao_deve_vender_sem_estoque_suficiente(): void
    {
        $produto = Produto::factory()->create([
            'estoque' => 1,
        ]);

        $payload = [
            'cliente' => 'Cliente Sem Sorte',
            'produtos' => [
                [
                    'id' => $produto->id,
                    'quantidade' => 2,
                    'preco_unitario' => 100.00,
                ],
            ],
        ];

        $response = $this->postJson('/api/vendas', $payload);

        $response->assertStatus(400)
            ->assertJsonFragment([
                'error' => 'Business Rule Violation',
            ]);
    }
}
