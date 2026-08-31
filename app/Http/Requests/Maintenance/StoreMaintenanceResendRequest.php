<?php

declare(strict_types=1);

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceResendRequest extends FormRequest
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
                'integer',
                'exists:companies,id',
            ],

            'origin_branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'sent_at' => [
                'required',
                'date',
            ],

            'invoice_number' => [
                'nullable',
                'string',
                'max:50',
            ],

            'defect_description' => [
                'required',
                'string',
                'min:3',
            ],

            'observation' => [
                'nullable',
                'string',
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
                'Informe a empresa responsável pelo reparo.',

            'company_id.exists' =>
                'A empresa informada não existe.',

            'origin_branch_id.required' =>
                'Informe a filial de origem.',

            'origin_branch_id.exists' =>
                'A filial de origem informada não existe.',

            'sent_at.required' =>
                'Informe a data e hora do reenvio.',

            'sent_at.date' =>
                'A data e hora do reenvio informadas são inválidas.',

            'invoice_number.max' =>
                'O número da nota fiscal não pode ter mais de 50 caracteres.',

            'defect_description.required' =>
                'Informe o defeito ou motivo do reenvio.',

            'defect_description.min' =>
                'A descrição do defeito ou motivo do reenvio deve possuir pelo menos 3 caracteres.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'company_id' =>
                'empresa responsável pelo reparo',

            'origin_branch_id' =>
                'filial de origem',

            'sent_at' =>
                'data e hora do reenvio',

            'invoice_number' =>
                'número da nota fiscal',

            'defect_description' =>
                'defeito ou motivo do reenvio',

            'observation' =>
                'observação',

        ];
    }
}
