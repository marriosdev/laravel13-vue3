<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\Response\VendaResponseDTO;
use App\DTOs\VendaDTO;
use App\Exceptions\BusinessHttpException;
use App\Models\Produto;
use App\Models\Venda;
use App\Models\VendaItem;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RegistrarVendaAction
{
    public function execute(VendaDTO $dto): VendaResponseDTO
    {
        return DB::transaction(function () use ($dto) {
            $valorTotal = 0;
            $lucroTotal = 0;

            $venda = Venda::create([
                'cliente' => $dto->cliente,
                'valor_total' => 0,
                'lucro_total' => 0,
            ]);

            foreach ($dto->produtos as $item) {
                $produto = Produto::lockForUpdate()->find($item['id']);

                if (! $produto) {
                    throw new NotFoundHttpException("Produto {$item['id']} não encontrado.");
                }

                if ($produto->estoque < $item['quantidade']) {
                    throw new BusinessHttpException("Estoque insuficiente para o produto {$produto->nome}. Disponível: {$produto->estoque}. Solicitado: {$item['quantidade']}.");
                }

                $itemTotal = (int) $item['quantidade'] * (int) $item['preco_unitario'];
                $itemLucro = ((int) $item['preco_unitario'] - $produto->custo_medio) * (int) $item['quantidade'];

                $valorTotal += $itemTotal;
                $lucroTotal += $itemLucro;

                VendaItem::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco_unitario'],
                    'lucro' => $itemLucro,
                ]);

                $produto->decrement('estoque', $item['quantidade']);
            }

            $venda->update([
                'valor_total' => $valorTotal,
                'lucro_total' => $lucroTotal,
            ]);

            return VendaResponseDTO::fromModel($venda->load('itens.produto'));
        });
    }
}
