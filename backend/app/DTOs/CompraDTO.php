<?php

declare(strict_types=1);

namespace App\DTOs;

final class CompraDTO extends BaseDTO
{
    public function __construct(
        public readonly string $fornecedor,
        public readonly array $produtos,
    ) {}

    public static function fromRequest($request): self
    {
        $produtos = collect($request->validated('produtos'))->map(function ($item) {
            return [
                'id' => $item['id'],
                'quantidade' => (int) $item['quantidade'],
                'preco_unitario' => (int) round($item['preco_unitario'] * 100),
            ];
        })->toArray();

        return new self(
            fornecedor: $request->validated('fornecedor'),
            produtos: $produtos,
        );
    }
}
