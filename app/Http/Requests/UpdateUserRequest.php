<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $rules = [

            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->route('user')),
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
            ],

            'active' => [
                'sometimes',
                'boolean',
            ],
        ];

        if ($this->user()->isAdmin()) {

            $rules['role_id'] = [
                'required',
                'exists:roles,id',
            ];
        }

        return $rules;
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'O nome não pode conter números ou caracteres especiais.',
        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'role_id' => 'perfil',
            'active' => 'status',
        ];
    }
}
