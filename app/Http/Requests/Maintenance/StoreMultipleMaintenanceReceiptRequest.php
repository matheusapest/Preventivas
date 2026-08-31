<?php

declare(strict_types=1);

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultipleMaintenanceReceiptRequest extends FormRequest
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

            'shipment_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'shipment_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:maintenance_shipments,id',
            ],

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

            'shipment_ids.required' =>
                'Selecione pelo menos um equipamento para realizar o recebimento.',

            'shipment_ids.array' =>
                'A lista de equipamentos selecionados é inválida.',

            'shipment_ids.min' =>
                'Selecione pelo menos um equipamento para realizar o recebimento.',

            'shipment_ids.*.integer' =>
                'Um dos equipamentos selecionados é inválido.',

            'shipment_ids.*.distinct' =>
                'O mesmo equipamento foi selecionado mais de uma vez.',

            'shipment_ids.*.exists' =>
                'Um dos envios selecionados não existe.',

            'invoice_number.max' =>
                'O número da nota fiscal não pode ter mais de 50 caracteres.',

            'receiving_branch_id.required' =>
                'A filial de recebimento é necessária para registrar o recebimento.',

            'receiving_branch_id.integer' =>
                'A filial de recebimento selecionada é inválida.',

            'receiving_branch_id.exists' =>
                'A filial de recebimento selecionada não existe.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'shipment_ids' =>
                'equipamentos selecionados',

            'invoice_number' =>
                'número da nota fiscal de retorno',

            'receiving_observation' =>
                'observação do recebimento',

            'receiving_branch_id' =>
                'Filial de recebimento',

        ];
    }
}
