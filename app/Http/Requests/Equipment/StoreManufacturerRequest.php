<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreManufacturerRequest extends FormRequest
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
        return [

            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('manufacturers', 'name'),
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

            'name.required' => 'Informe o nome do fabricante.',
            'name.unique' => 'Já existe um fabricante cadastrado com este nome.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'name' => 'nome',

        ];
    }
}
