<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\ProdutoDTO;
use App\DTOs\Response\ProdutoResponseDTO;
use App\Models\Produto;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class AtualizarProdutoAction
{
    public function execute(int $id, ProdutoDTO $dto): ProdutoResponseDTO
    {
        $produto = Produto::find($id);

        if (! $produto) {
            throw new NotFoundHttpException("Produto com ID {$id} não encontrado.");
        }

        $produto->update($dto->toArray());

        return ProdutoResponseDTO::fromModel($produto);
    }
}
