<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchCodeRequest extends FormRequest
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

            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('branch_codes', 'code')
                    ->ignore($this->route('branchCode')),
            ],

            'name' => [
                'nullable',
                'string',
                'max:150',
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

            'code.unique' => 'Já existe um código de filial cadastrado com este código.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'code' => 'código da filial',
            'name' => 'nome',
            'active' => 'status',

        ];
    }
}
