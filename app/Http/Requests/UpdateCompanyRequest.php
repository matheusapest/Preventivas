<?php

namespace App\Http\Requests;

use App\Enums\CompanyType;
use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateCompanyRequest extends FormRequest
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
                Rule::unique(Company::class, 'name')
                    ->ignore($this->route('company')),
            ],

            'type' => [
                'required',
                new Enum(CompanyType::class),
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
            'name.required' =>
                'Informe o nome da empresa.',

            'name.max' =>
                'O nome da empresa não pode ter mais de 150 caracteres.',

            'name.unique' =>
                'Já existe uma empresa cadastrada com este nome.',

            'type.required' =>
                'Informe o tipo da empresa.',

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
            'name' => 'nome',
            'type' => 'tipo',
            'active' => 'status',
        ];
    }
}
