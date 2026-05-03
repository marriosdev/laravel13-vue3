<?php

namespace App\Http\Requests;

use App\Traits\HasValidateErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class RegistrarVendaRequest extends FormRequest
{
    use HasValidateErrorResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente' => 'required|string|max:255',
            'produtos' => 'required|array|min:1',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco_unitario' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'cliente.required' => 'O nome do cliente é obrigatório para registrar a venda.',
            'produtos.required' => 'Você precisa adicionar pelo menos um produto na venda.',
            'produtos.min' => 'A venda deve ter no mínimo 1 produto.',
            'produtos.*.id.exists' => 'Um dos produtos selecionados não existe no sistema.',
            'produtos.*.quantidade.min' => 'A quantidade de cada produto deve ser pelo menos 1.',
            'produtos.*.preco_unitario.min' => 'O preço unitário não pode ser negativo.',
        ];
    }
}
