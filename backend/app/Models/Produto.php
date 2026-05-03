<?php

namespace App\Models;

use App\Observers\ProdutoObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(ProdutoObserver::class)]
class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nome', 'custo_medio', 'preco_venda', 'estoque'];
}
