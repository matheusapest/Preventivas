<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUnitTypeRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:unit_types,name',
            ],

            'active' => [
                'boolean',
            ],

            'branches' => [
                'required',
                'array',
                'min:1',
            ],

            'branches.*' => [
                'required',
                'integer',
                Rule::exists('branches', 'id')
                    ->where('active', true),
            ],
        ];
    }

    /**
     * Mensagens de validação.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do tipo de unidade é obrigatório.',

            'name.max' => 'O nome do tipo de unidade não pode ultrapassar 255 caracteres.',

            'name.unique' => 'Já existe um tipo de unidade com este nome.',

            'active.boolean' => 'O status informado é inválido.',

            'branches.required' => 'Selecione pelo menos uma filial.',

            'branches.array' => 'As filiais selecionadas são inválidas.',

            'branches.min' => 'Selecione pelo menos uma filial.',

            'branches.*.required' => 'Uma das filiais selecionadas é inválida.',

            'branches.*.integer' => 'Uma das filiais selecionadas é inválida.',

            'branches.*.exists' => 'Uma das filiais selecionadas não existe ou está inativa.',
        ];
    }
}
