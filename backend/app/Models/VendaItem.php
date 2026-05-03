<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendaItem extends Model
{
    protected $table = 'venda_itens';

    protected $fillable = ['venda_id', 'produto_id', 'quantidade', 'preco_unitario', 'lucro'];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
