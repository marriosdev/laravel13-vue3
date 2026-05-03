<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\Response\ProdutoResponseDTO;
use App\Models\Produto;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ObterProdutoAction
{
    public function execute(int $id): ProdutoResponseDTO
    {
        $produto = Produto::find($id);

        if (! $produto) {
            throw new NotFoundHttpException("Produto com ID {$id} não encontrado.");
        }

        return ProdutoResponseDTO::fromModel($produto);
    }
}
