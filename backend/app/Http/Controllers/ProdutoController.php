<?php

namespace App\Http\Controllers;

use App\Actions\AtualizarProdutoAction;
use App\Actions\CriarProdutoAction;
use App\Actions\DeletarProdutoAction;
use App\Actions\ListarProdutosAction;
use App\Actions\ObterProdutoAction;
use App\Actions\ObterProdutosParaSelectAction;
use App\DTOs\ProdutoDTO;
use App\Http\Requests\CadastrarProdutoRequest;
use Illuminate\Routing\Controller;

class ProdutoController extends Controller
{
    public function index(ListarProdutosAction $action)
    {
        return $action->execute();
    }

    public function select(ObterProdutosParaSelectAction $action)
    {
        return $action->execute();
    }

    public function store(CadastrarProdutoRequest $request, CriarProdutoAction $action)
    {
        return $action->execute(ProdutoDTO::fromRequest($request))->toArray();
    }

    public function show(int $id, ObterProdutoAction $action)
    {
        return $action->execute($id)->toArray();
    }

    public function update(CadastrarProdutoRequest $request, int $id, AtualizarProdutoAction $action)
    {
        return $action->execute($id, ProdutoDTO::fromRequest($request))->toArray();
    }

    public function destroy(int $id, DeletarProdutoAction $action)
    {
        $action->execute($id);

        return response()->noContent();
    }
}
