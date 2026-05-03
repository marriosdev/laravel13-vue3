<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\Response\ProdutoResponseDTO;
use App\Models\Produto;
use Illuminate\Pagination\LengthAwarePaginator;

final class ListarProdutosAction
{
    public function execute(): LengthAwarePaginator
    {
        $produtos = Produto::orderBy('nome')
            ->paginate(10)
            ->through(fn ($p) => ProdutoResponseDTO::fromModel($p));

        return $produtos;
    }
}
