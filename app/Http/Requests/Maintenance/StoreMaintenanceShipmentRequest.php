<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceShipmentRequest extends FormRequest
{
    /**
     * Determina se o usuário pode realizar esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sent_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }
    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [

            'equipment_id' => [
                'required',
                'integer',
                'exists:equipments,id',
            ],

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
                'after_or_equal:' . now()->subDays(7)->startOfDay()->toDateTimeString(),
                'before_or_equal:' . now()->endOfDay()->toDateTimeString(),
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

            'equipment_id.required' =>
            'Informe o equipamento.',

            'equipment_id.exists' =>
            'O equipamento informado não foi encontrado.',

            'company_id.required' =>
            'Informe a empresa terceirizada.',

            'company_id.exists' =>
            'A empresa terceirizada não foi encontrada.',

            'origin_branch_id.required' =>
            'Informe a filial de origem.',

            'origin_branch_id.exists' =>
            'A filial de origem não foi encontrada.',

            'sent_at.required' =>
            'Informe a data de envio.',

            'sent_at.date' =>
            'A data de envio informada é inválida.',

            'sent_at.after_or_equal' =>
            'A data de envio não pode ser anterior a 7 dias.',

            'sent_at.before_or_equal' =>
            'A data de envio não pode ser uma data futura.',

            'invoice_number.max' =>
            'O número da nota fiscal não pode ter mais de 50 caracteres.',

            'defect_description.required' =>
            'Informe o defeito apresentado pelo equipamento.',

            'defect_description.min' =>
            'A descrição do defeito deve possuir pelo menos 3 caracteres.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'equipment_id' => 'equipamento',

            'company_id' => 'empresa terceirizada',

            'origin_branch_id' => 'filial de origem',

            'sent_at' => 'data de envio',

            'invoice_number' => 'número da nota fiscal',

            'defect_description' => 'defeito',

            'observation' => 'observação',

        ];
    }
}
