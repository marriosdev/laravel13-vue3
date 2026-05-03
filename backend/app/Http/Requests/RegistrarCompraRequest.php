<?php

namespace App\Http\Requests;

use App\Traits\HasValidateErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class RegistrarCompraRequest extends FormRequest
{
    use HasValidateErrorResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fornecedor' => 'required|string|max:255',
            'produtos' => 'required|array|min:1',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco_unitario' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'fornecedor.required' => 'O nome do fornecedor é indispensável.',
            'produtos.required' => 'A compra deve ter pelo menos um item.',
            'produtos.min' => 'Selecione no mínimo 1 produto.',
            'produtos.*.id.exists' => 'Produto não encontrado no catálogo.',
            'produtos.*.quantidade.min' => 'A quantidade comprada deve ser maior que zero.',
            'produtos.*.preco_unitario.min' => 'O preço de custo não pode ser negativo.',
        ];
    }
}
