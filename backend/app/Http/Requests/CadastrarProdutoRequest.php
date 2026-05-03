<?php

namespace App\Http\Requests;

use App\Traits\HasValidateErrorResponse;
use Illuminate\Foundation\Http\FormRequest;

class CadastrarProdutoRequest extends FormRequest
{
    use HasValidateErrorResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|min:3|max:255',
            'preco_venda' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Você esqueceu de dar um nome para o produto!',
            'nome.min' => 'O nome do produto deve ser um pouquinho maior (mínimo 3 letras).',
            'preco_venda.required' => 'Todo produto precisa de um preço de venda.',
            'preco_venda.numeric' => 'O preço deve ser um número válido.',
            'preco_venda.min' => 'O preço de venda não pode ser negativo.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nome' => 'nome',
            'preco_venda' => 'preço de venda',
        ];
    }
}
