<?php

namespace Tests\Unit\Actions;

use App\Actions\RegistrarVendaAction;
use App\DTOs\VendaDTO;
use App\Exceptions\BusinessHttpException;
use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrarVendaActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_executar_calcula_lucro_e_baixa_estoque(): void
    {
        $produto = Produto::factory()->create([
            'estoque' => 50,
            'custo_medio' => 1500, // R$ 15.00
            'preco_venda' => 3000,
        ]);

        $dto = new VendaDTO(
            cliente: 'Cliente Teste',
            produtos: [
                [
                    'id' => $produto->id,
                    'quantidade' => 10,
                    'preco_unitario' => 3000, // Centavos no DTO
                ],
            ]
        );

        $action = new RegistrarVendaAction;
        $response = $action->execute($dto);

        // Venda de 10 por 30.00 = 300.00
        // Custo = 15.00 * 10 = 150.00
        // Lucro = 150.00

        $this->assertEquals(300.0, $response->valor_total);
        $this->assertEquals(150.0, $response->lucro_total);

        $produtoAtualizado = Produto::find($produto->id);
        $this->assertEquals(40, $produtoAtualizado->estoque);
    }

    public function test_executar_lanca_excecao_se_falta_estoque(): void
    {
        $produto = Produto::factory()->create([
            'estoque' => 5,
        ]);

        $dto = new VendaDTO(
            cliente: 'Cliente Teste',
            produtos: [
                [
                    'id' => $produto->id,
                    'quantidade' => 10,
                    'preco_unitario' => 3000,
                ],
            ]
        );

        $action = new RegistrarVendaAction;

        $this->expectException(BusinessHttpException::class);
        $action->execute($dto);
    }
}
