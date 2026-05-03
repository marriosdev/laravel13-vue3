<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\CompraDTO;
use App\DTOs\Response\CompraResponseDTO;
use App\Models\Compra;
use App\Models\CompraItem;
use App\Models\Produto;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RegistrarCompraAction
{
    public function execute(CompraDTO $dto): CompraResponseDTO
    {
        return DB::transaction(function () use ($dto) {
            $custoTotal = 0;

            $compra = Compra::create([
                'fornecedor' => $dto->fornecedor,
                'custo_total' => 0,
            ]);

            foreach ($dto->produtos as $item) {
                $produto = Produto::lockForUpdate()->find($item['id']);

                if (! $produto) {
                    throw new NotFoundHttpException("Produto {$item['id']} não encontrado.");
                }
                $itemTotal = (int) $item['quantidade'] * (int) $item['preco_unitario'];
                $custoTotal += $itemTotal;

                $valorTotalAtual = $produto->estoque * $produto->custo_medio;
                $novaQuantidadeTotal = $produto->estoque + $item['quantidade'];

                $novoCustoMedio = (int) round(($valorTotalAtual + $itemTotal) / $novaQuantidadeTotal);

                $produto->update([
                    'estoque' => $novaQuantidadeTotal,
                    'custo_medio' => $novoCustoMedio,
                ]);

                CompraItem::create([
                    'compra_id' => $compra->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco_unitario'],
                ]);
            }

            $compra->update(['custo_total' => $custoTotal]);

            return CompraResponseDTO::fromModel($compra->load('itens.produto'));
        });
    }
}
