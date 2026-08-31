<?php

namespace App\Http\Requests\Configuration\Preventive;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePreventiveTypeRequest extends FormRequest
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
        $preventiveType = $this->route('preventiveType');

        return [
            'name' => [
                'required',
                'string',
                'max:255',

                Rule::unique('preventive_types', 'name')
                    ->where(
                        'unit_type_id',
                        $preventiveType->unit_type_id
                    )
                    ->ignore($preventiveType),
            ],

            'active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Mensagens de validação.
     */
    public function messages(): array
    {
        return [
            'name.required' =>
                'O nome do tipo de preventiva é obrigatório.',

            'name.max' =>
                'O nome do tipo de preventiva não pode ultrapassar 255 caracteres.',

            'name.unique' =>
                'Já existe um tipo de preventiva com este nome para o tipo de unidade selecionado.',

            'active.boolean' =>
                'O status informado é inválido.',
        ];
    }

    /**
     * Nomes amigáveis dos atributos.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'active' => 'status',
        ];
    }
}
