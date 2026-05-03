<?php

use App\Http\Controllers\CompraController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard/estatisticas', [DashboardController::class, 'stats']);

Route::get('produtos/select', [ProdutoController::class, 'select']);
Route::apiResource('produtos', ProdutoController::class);
Route::apiResource('compras', CompraController::class)->only(['index', 'store']);
Route::apiResource('vendas', VendaController::class)->only(['index', 'store']);
Route::post('vendas/{id}/cancelar', [VendaController::class, 'cancel']);
