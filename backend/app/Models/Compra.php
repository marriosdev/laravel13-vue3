<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Compra extends Model
{
    protected $fillable = ['fornecedor', 'custo_total'];

    public function itens(): HasMany
    {
        return $this->hasMany(CompraItem::class);
    }
}
