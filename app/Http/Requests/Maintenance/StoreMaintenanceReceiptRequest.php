<?php

declare(strict_types=1);

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceReceiptRequest extends FormRequest
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

            'invoice_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'receiving_observation' => [
                'nullable',
                'string',
            ],

            'receiving_branch_id' => [
                'required',
                'integer',
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

            'invoice_number.max' =>
            'O número da nota fiscal não pode ter mais de 50 caracteres.',
            'receiving_branch_id.required' =>
            'A filial de recebimento é necessária para registrar o recebimento.',
            'receiving_branch_id.exists' =>
            'A filial de recebimento selecionada não existe.',
            'receiving_branch_id.integer' =>
            'A filial de recebimento selecionada é inválida.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'invoice_number' =>
            'número da nota fiscal de retorno',

            'receiving_observation' =>
            'observação do recebimento',

            'receiving_branch_id' => 'Filial de recebimento'

        ];
    }
}
