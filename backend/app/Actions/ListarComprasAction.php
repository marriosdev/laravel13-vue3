<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\Response\CompraResponseDTO;
use App\Models\Compra;
use Illuminate\Pagination\LengthAwarePaginator;

final class ListarComprasAction
{
    public function execute(): LengthAwarePaginator
    {
        return Compra::with(['itens.produto' => fn ($q) => $q->withTrashed()])
            ->latest()
            ->paginate(10)
            ->through(fn ($c) => CompraResponseDTO::fromModel($c));
    }
}
