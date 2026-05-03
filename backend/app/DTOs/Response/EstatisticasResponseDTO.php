<?php

declare(strict_types=1);

namespace App\DTOs\Response;

use App\DTOs\BaseDTO;
use Illuminate\Support\Collection;

final class EstatisticasResponseDTO extends BaseDTO
{
    public function __construct(
        public readonly int $total_produtos,
        public readonly float $valor_total_estoque,
        public readonly float $vendas_totais,
        public readonly float $lucro_total,
        public readonly array|Collection $vendas_recentes,
        public readonly array|Collection $produtos_estoque_baixo,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            total_produtos: $data['total_produtos'],
            valor_total_estoque: $data['valor_total_estoque'] / 100,
            vendas_totais: $data['vendas_totais'] / 100,
            lucro_total: $data['lucro_total'] / 100,
            vendas_recentes: $data['vendas_recentes']->map(function ($item) {
                return VendaResponseDTO::fromModel($item)->toArray();
            }),
            produtos_estoque_baixo: $data['produtos_estoque_baixo']->map(function ($item) {
                return ProdutoResponseDTO::fromModel($item)->toArray();
            }),
        );
    }
}
