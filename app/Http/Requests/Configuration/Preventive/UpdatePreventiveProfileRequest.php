<?php

declare(strict_types=1);

namespace App\Http\Requests\Configuration\Preventive;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreventiveProfileRequest extends FormRequest
{
    /**
     * Determina se o usuário pode realizar a requisição.
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
                'max:1000',
            ],

            'preventive_type_id' => [
                'required',
                'integer',
                'exists:preventive_types,id',
            ],

            'active' => [
                'sometimes',
                'boolean',
            ],

            'branch_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'branch_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:branches,id',
            ],
        ];
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [
            'name.required' =>
                'O nome do perfil é obrigatório.',

            'name.max' =>
                'O nome do perfil não pode ultrapassar 255 caracteres.',

            'description.max' =>
                'A descrição não pode ultrapassar 1000 caracteres.',

            'preventive_type_id.required' =>
                'O tipo de preventiva é obrigatório.',

            'preventive_type_id.integer' =>
                'O tipo de preventiva informado é inválido.',

            'preventive_type_id.exists' =>
                'O tipo de preventiva informado não existe.',

            'active.boolean' =>
                'O status informado é inválido.',

            'branch_ids.required' =>
                'É necessário selecionar pelo menos uma filial.',

            'branch_ids.array' =>
                'A seleção de filiais é inválida.',

            'branch_ids.min' =>
                'É necessário selecionar pelo menos uma filial.',

            'branch_ids.*.required' =>
                'A filial é obrigatória.',

            'branch_ids.*.integer' =>
                'A filial informada é inválida.',

            'branch_ids.*.distinct' =>
                'Uma filial não pode ser selecionada mais de uma vez.',

            'branch_ids.*.exists' =>
                'A filial informada não existe.',
        ];
    }
}
