<?php

namespace App\Http\Requests\Organization;

use App\Enums\BranchType;
use App\Enums\State;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateBranchRequest extends FormRequest
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
            'company_id' => [
                'required',
                'exists:companies,id',
            ],

            'branch_code_id' => [
                'required',
                'exists:branch_codes,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'state' => [
                'required',
                new Enum(State::class),
            ],

            'type' => [
                'required',
                new Enum(BranchType::class),
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
            'company_id.required' =>
                'Informe a empresa.',

            'company_id.exists' =>
                'A empresa informada não foi encontrada.',

            'branch_code_id.required' =>
                'Informe o código da filial.',

            'branch_code_id.exists' =>
                'O código da filial informado não foi encontrado.',

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
            'company_id' => 'empresa',
            'branch_code_id' => 'código da filial',
            'name' => 'nome',
            'city' => 'cidade',
            'state' => 'estado',
            'type' => 'tipo',
            'active' => 'status',
        ];
    }
}
