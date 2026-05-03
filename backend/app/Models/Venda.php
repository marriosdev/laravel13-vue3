<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    protected $fillable = ['cliente', 'valor_total', 'lucro_total', 'cancelada'];

    public function itens(): HasMany
    {
        return $this->hasMany(VendaItem::class);
    }
}
