<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Produto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class ObterProdutosParaSelectAction
{
    public function execute(): ?Collection
    {
        $data = Cache::remember('produtos_para_select', 3600, function () {
            return Produto::get(['id', 'nome', 'preco_venda'])
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'nome' => $p->nome,
                    'preco_venda' => $p->preco_venda / 100,
                ])->all();
        });

        return $data ? collect($data) : null;
    }
}
