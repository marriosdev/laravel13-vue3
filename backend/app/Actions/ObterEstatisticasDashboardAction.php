<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\Response\EstatisticasResponseDTO;
use App\Models\Produto;
use App\Models\Venda;

final class ObterEstatisticasDashboardAction
{
    public function execute(): EstatisticasResponseDTO
    {
        $stats = [
            'total_produtos' => Produto::count(),
            'valor_total_estoque' => (int) Produto::get()->sum(fn ($p) => $p->estoque * $p->custo_medio),
            'vendas_totais' => (int) Venda::where('cancelada', false)->sum('valor_total'),
            'lucro_total' => (int) Venda::where('cancelada', false)->sum('lucro_total'),
            'vendas_recentes' => Venda::with(['itens.produto' => fn ($q) => $q->withTrashed()])->where('cancelada', false)->latest()->take(5)->get(),
            'produtos_estoque_baixo' => Produto::where('estoque', '<=', 5)->orderBy('estoque', 'asc')->take(5)->get(),
        ];

        return EstatisticasResponseDTO::fromArray($stats);
    }
}
