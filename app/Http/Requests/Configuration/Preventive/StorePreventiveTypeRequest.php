<?php

namespace App\Http\Requests\Configuration\Preventive;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreventiveTypeRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a realizar a requisição.
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
            'unit_type_id' => [
                'required',
                'integer',
                Rule::exists('unit_types', 'id')
                    ->where('active', true),
            ],

            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('preventive_types', 'name')
                    ->where('unit_type_id', $this->unit_type_id),
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
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
            'unit_type_id.required' =>
                'O tipo de unidade é obrigatório.',

            'unit_type_id.exists' =>
                'O tipo de unidade selecionado não existe ou está inativo.',

            'name.required' =>
                'O nome do tipo de preventiva é obrigatório.',

            'name.max' =>
                'O nome do tipo de preventiva não pode ultrapassar 150 caracteres.',

            'name.unique' =>
                'Já existe um tipo de preventiva com este nome para o tipo de unidade selecionado.',

            'description.max' =>
                'A descrição não pode ultrapassar 1000 caracteres.',

            'active.boolean' =>
                'O status informado é inválido.',
        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [
            'unit_type_id' => 'tipo de unidade',
            'name' => 'nome',
            'description' => 'descrição',
            'active' => 'status',
        ];
    }
}
