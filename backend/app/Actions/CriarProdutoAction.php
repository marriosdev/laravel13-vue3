<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\ProdutoDTO;
use App\DTOs\Response\ProdutoResponseDTO;
use App\Models\Produto;

final class CriarProdutoAction
{
    public function execute(ProdutoDTO $dto): ProdutoResponseDTO
    {
        $produto = Produto::create($dto->toArray());

        return ProdutoResponseDTO::fromModel($produto);
    }
}
