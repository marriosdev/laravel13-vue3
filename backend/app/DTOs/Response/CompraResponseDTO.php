<?php

declare(strict_types=1);

namespace App\DTOs\Response;

use App\DTOs\BaseDTO;
use App\Models\Compra;

final class CompraResponseDTO extends BaseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $fornecedor,
        public readonly float $custo_total,
        public readonly array $itens,
        public readonly string $criado_em,
    ) {}

    public static function fromModel(Compra $compra): self
    {
        return new self(
            id: $compra->id,
            fornecedor: $compra->fornecedor,
            custo_total: $compra->custo_total / 100,
            itens: $compra->itens->map(fn ($item) => [
                'id' => $item->id,
                'produto_nome' => $item->produto->nome,
                'quantidade' => $item->quantidade,
                'preco_unitario' => $item->preco_unitario / 100,
            ])->toArray(),
            criado_em: $compra->created_at->toISOString(),
        );
    }
}
