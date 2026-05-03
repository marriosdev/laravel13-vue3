<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\Response\VendaResponseDTO;
use App\Models\Venda;
use Illuminate\Pagination\LengthAwarePaginator;

final class ListarVendasAction
{
    public function execute(): LengthAwarePaginator
    {
        return Venda::with(['itens.produto' => fn ($q) => $q->withTrashed()])
            ->latest()
            ->paginate(10)
            ->through(fn ($v) => VendaResponseDTO::fromModel($v));
    }
}
