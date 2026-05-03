<?php

namespace App\Http\Controllers;

use App\Actions\ListarComprasAction;
use App\Actions\RegistrarCompraAction;
use App\DTOs\CompraDTO;
use App\Http\Requests\RegistrarCompraRequest;
use Illuminate\Routing\Controller;

class CompraController extends Controller
{
    public function index(ListarComprasAction $action)
    {
        return $action->execute();
    }

    public function store(RegistrarCompraRequest $request, RegistrarCompraAction $action)
    {
        return $action->execute(CompraDTO::fromRequest($request))->toArray();
    }
}
