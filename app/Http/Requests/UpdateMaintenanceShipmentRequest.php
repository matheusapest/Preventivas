<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceShipmentRequest extends FormRequest
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

            'origin_branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'invoice_number' => [
                'nullable',
                'string',
                'max:50',
            ],

        ];
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [

            'origin_branch_id.required' =>
                'A filial de origem é necessária.',

            'origin_branch_id.integer' =>
                'A filial de origem selecionada é inválida.',

            'origin_branch_id.exists' =>
                'A filial de origem selecionada não existe.',

            'invoice_number.max' =>
                'O número da nota fiscal não pode ter mais de 50 caracteres.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'origin_branch_id' =>
                'filial de origem',

            'invoice_number' =>
                'número da nota fiscal',

        ];
    }
}
