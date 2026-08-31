<?php

declare(strict_types=1);

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceReceiptRequest extends FormRequest
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

            'receiving_branch_id' => [
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

            'receiving_branch_id.required' =>
                'A filial de recebimento é necessária.',

            'receiving_branch_id.integer' =>
                'A filial de recebimento selecionada é inválida.',

            'receiving_branch_id.exists' =>
                'A filial de recebimento selecionada não existe.',

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

            'receiving_branch_id' =>
                'filial de recebimento',

            'invoice_number' =>
                'número da nota fiscal de retorno',

        ];
    }
}
