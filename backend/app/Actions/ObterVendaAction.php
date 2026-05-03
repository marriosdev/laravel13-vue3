<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\Response\VendaResponseDTO;
use App\Models\Venda;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ObterVendaAction
{
    public function execute(int $id): VendaResponseDTO
    {
        $venda = Venda::with(['itens.produto' => fn ($q) => $q->withTrashed()])->find($id);

        if (! $venda) {
            throw new NotFoundHttpException("Venda com ID {$id} não encontrada.");
        }

        return VendaResponseDTO::fromModel($venda);
    }
}
