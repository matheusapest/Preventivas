<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityCategoryRequest extends FormRequest
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
                'max:100',
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
                'O nome da categoria é obrigatório.',

            'name.max' =>
                'O nome da categoria não pode ultrapassar 100 caracteres.',

            'active.boolean' =>
                'O status informado é inválido.',
        ];
    }
}
