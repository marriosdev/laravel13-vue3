<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Produto;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DeletarProdutoAction
{
    public function execute(int $id): void
    {
        $produto = Produto::find($id);

        if (! $produto) {
            throw new NotFoundHttpException("Produto com ID {$id} não encontrado.");
        }

        $produto->delete();
    }
}
