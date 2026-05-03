<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\Response\VendaResponseDTO;
use App\Exceptions\BusinessHttpException;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CancelarVendaAction
{
    public function execute(int $id): VendaResponseDTO
    {
        return DB::transaction(function () use ($id) {
            $venda = Venda::with('itens')->find($id);

            if (! $venda) {
                throw new NotFoundHttpException("Venda com ID {$id} não encontrada.");
            }

            if ($venda->cancelada) {
                throw new BusinessHttpException("Venda com ID {$id} já está cancelada.");
            }

            foreach ($venda->itens as $item) {
                $produto = Produto::withTrashed()->lockForUpdate()->find($item->produto_id);
                $produto->increment('estoque', $item->quantidade);
            }

            $venda->update(['cancelada' => true]);

            return VendaResponseDTO::fromModel($venda->load(['itens.produto' => fn ($q) => $q->withTrashed()]));
        });
    }
}
