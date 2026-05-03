<?php

namespace Tests\Unit\Actions;

use App\Actions\RegistrarCompraAction;
use App\DTOs\CompraDTO;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrarCompraActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_executar_calcula_custo_medio_corretamente(): void
    {
        $produto = Produto::factory()->create([
            'estoque' => 10,
            'custo_medio' => 2000, // R$ 20.00
            'preco_venda' => 4000,
        ]);

        /**
         * Valor do estoque atual: 10 * 20.00 = 200.00
         * Nova compra: 10 itens a 40.00 = 400.00
         * Total esperado: 600.00 / 20 = 30.00 (3000 centavos)
         */
        $dto = new CompraDTO(
            fornecedor: 'Fornecedor Teste',
            produtos: [
                [
                    'id' => $produto->id,
                    'quantidade' => 10,
                    'preco_unitario' => 4000,
                ],
            ]
        );

        $action = new RegistrarCompraAction;
        $response = $action->execute($dto);

        $this->assertEquals(40.0, $response->itens[0]['produto_nome'] ? $response->itens[0]['preco_unitario'] : 0); // apenas para forçar avaliação

        $produtoAtualizado = Produto::find($produto->id);

        $this->assertEquals(20, $produtoAtualizado->estoque);
        $this->assertEquals(3000, $produtoAtualizado->custo_medio);
    }
}
