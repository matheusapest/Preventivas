<?php

namespace App\Http\Requests;

use App\Enums\ActivityKind;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActivityRequest extends FormRequest
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
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                Rule::enum(ActivityKind::class),
            ],

            'activity_category_id' => [
                'required',
                'integer',
                'exists:activity_categories,id',
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
                'O nome da atividade é obrigatório.',

            'name.max' =>
                'O nome da atividade não pode ultrapassar 255 caracteres.',

            'description.string' =>
                'A descrição da atividade deve ser um texto válido.',

            'type.required' =>
                'O tipo da atividade é obrigatório.',

            'type.enum' =>
                'O tipo de atividade selecionado é inválido.',

            'activity_category_id.required' =>
                'A categoria da atividade é obrigatória.',

            'activity_category_id.integer' =>
                'A categoria da atividade selecionada é inválida.',

            'activity_category_id.exists' =>
                'A categoria de atividade selecionada não existe.',

            'active.boolean' =>
                'O status informado é inválido.',
        ];
    }
}
