<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->words(3, true),
            'custo_medio' => 0,
            'preco_venda' => $this->faker->numberBetween(5000, 20000), // 50.00 to 200.00
            'estoque' => 0,
        ];
    }
}
