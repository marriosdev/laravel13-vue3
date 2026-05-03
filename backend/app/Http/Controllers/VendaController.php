<?php

namespace App\Http\Controllers;

use App\Actions\CancelarVendaAction;
use App\Actions\ListarVendasAction;
use App\Actions\ObterVendaAction;
use App\Actions\RegistrarVendaAction;
use App\DTOs\VendaDTO;
use App\Http\Requests\RegistrarVendaRequest;
use Illuminate\Routing\Controller;

class VendaController extends Controller
{
    public function index(ListarVendasAction $action)
    {
        return $action->execute();
    }

    public function store(RegistrarVendaRequest $request, RegistrarVendaAction $action)
    {
        return $action->execute(VendaDTO::fromRequest($request))->toArray();
    }

    public function show(int $id, ObterVendaAction $action)
    {
        return $action->execute($id)->toArray();
    }

    public function cancel(int $id, CancelarVendaAction $action)
    {
        return $action->execute($id)->toArray();
    }
}
