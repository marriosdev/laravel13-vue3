<?php

namespace Tests\Feature;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompraTest extends TestCase
{
    use RefreshDatabase;

    public function test_deve_registrar_compra_e_atualizar_estoque_e_custo_medio(): void
    {
        $produto = Produto::factory()->create([
            'estoque' => 0,
            'custo_medio' => 0,
            'preco_venda' => 10000, // 100.00
        ]);

        $payload = [
            'fornecedor' => 'Fornecedor Ninja',
            'produtos' => [
                [
                    'id' => $produto->id,
                    'quantidade' => 10,
                    'preco_unitario' => 50.00,
                ],
            ],
        ];

        $response = $this->postJson('/api/compras', $payload);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'fornecedor' => 'Fornecedor Ninja',
                'custo_total' => 500.0,
            ]);

        $this->assertDatabaseHas('compras', [
            'fornecedor' => 'Fornecedor Ninja',
            'custo_total' => 50000,
        ]);

        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'estoque' => 10,
            'custo_medio' => 5000, // 50.00
        ]);
    }
}
