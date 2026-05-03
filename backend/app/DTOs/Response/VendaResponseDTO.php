<?php

declare(strict_types=1);

namespace App\DTOs\Response;

use App\DTOs\BaseDTO;
use App\Models\Venda;

final class VendaResponseDTO extends BaseDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $cliente,
        public readonly float $valor_total,
        public readonly float $lucro_total,
        public readonly bool $cancelada,
        public readonly array $itens,
        public readonly string $criado_em,
    ) {}

    public static function fromModel(Venda $venda): self
    {
        return new self(
            id: $venda->id,
            cliente: $venda->cliente,
            valor_total: $venda->valor_total / 100,
            lucro_total: $venda->lucro_total / 100,
            cancelada: (bool) $venda->cancelada,
            itens: $venda->itens->map(fn ($item) => [
                'id' => $item->id,
                'produto_nome' => $item->produto->nome,
                'quantidade' => $item->quantidade,
                'preco_unitario' => $item->preco_unitario / 100,
                'lucro' => $item->lucro / 100,
            ])->toArray(),
            criado_em: $venda->created_at->toISOString(),
        );
    }
}
