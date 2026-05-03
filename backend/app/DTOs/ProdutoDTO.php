<?php

declare(strict_types=1);

namespace App\DTOs;

final class ProdutoDTO extends BaseDTO
{
    public function __construct(
        public readonly string $nome,
        public readonly int $preco_venda,
        public readonly int $estoque = 0,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            nome: $request->validated('nome'),
            preco_venda: (int) round($request->validated('preco_venda') * 100),
            estoque: 0,
        );
    }
}
