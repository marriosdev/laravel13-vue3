<?php

declare(strict_types=1);

namespace App\DTOs\Response;

use App\DTOs\BaseDTO;
use App\Models\Produto;

final class ProdutoResponseDTO extends BaseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $nome,
        public readonly float $preco_venda,
        public readonly int $estoque,
        public readonly float $custo_medio,
        public readonly string $criado_em,
    ) {}

    public static function fromModel(Produto $produto): self
    {
        return new self(
            id: $produto->id,
            nome: $produto->nome,
            preco_venda: $produto->preco_venda / 100,
            estoque: $produto->estoque,
            custo_medio: $produto->custo_medio / 100,
            criado_em: $produto->created_at->toISOString(),
        );
    }
}
