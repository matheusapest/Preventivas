<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determina se o usuário pode realizar esta requisição.
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
        $category = $this->route('category');

        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('categories', 'name')
                    ->ignore($category),
            ],

            'unit_type_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'unit_type_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('unit_types', 'id')
                    ->where('active', true),
            ],

            'active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome da categoria.',

            'name.unique' => 'Já existe uma categoria cadastrada com este nome.',

            'unit_type_ids.required' => 'Informe pelo menos um tipo de unidade.',

            'unit_type_ids.array' => 'Os tipos de unidade selecionados são inválidos.',

            'unit_type_ids.min' => 'Selecione pelo menos um tipo de unidade.',

            'unit_type_ids.*.integer' => 'Um dos tipos de unidade selecionados é inválido.',

            'unit_type_ids.*.distinct' => 'Não é permitido selecionar o mesmo tipo de unidade mais de uma vez.',

            'unit_type_ids.*.exists' => 'Um dos tipos de unidade selecionados não existe ou está inativo.',
        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'unit_type_ids' => 'tipos de unidade',
            'unit_type_ids.*' => 'tipo de unidade',
        ];
    }
}
