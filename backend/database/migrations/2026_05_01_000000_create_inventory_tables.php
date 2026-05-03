<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('venda_itens');
        Schema::dropIfExists('vendas');
        Schema::dropIfExists('compra_itens');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('produtos');

        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('preco_venda');
            $table->integer('estoque')->default(0);
            $table->integer('custo_medio')->default(0);
            $table->timestamps();
        });

        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('fornecedor');
            $table->integer('custo_total');
            $table->timestamps();
        });

        Schema::create('compra_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos');
            $table->integer('quantidade');
            $table->integer('preco_unitario');
            $table->timestamps();
        });

        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->string('cliente');
            $table->integer('valor_total');
            $table->integer('lucro_total');
            $table->boolean('cancelada')->default(false);
            $table->timestamps();
        });

        Schema::create('venda_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained('vendas')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos');
            $table->integer('quantidade');
            $table->integer('preco_unitario');
            $table->integer('lucro');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venda_itens');
        Schema::dropIfExists('vendas');
        Schema::dropIfExists('compra_itens');
        Schema::dropIfExists('compras');
        Schema::dropIfExists('produtos');
    }
};
