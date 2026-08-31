<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
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

            'equipment_id' => [
                'required',
                'integer',
                'exists:equipments,id',
            ],

            'destination_branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'observation' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ];
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [

            'equipment_id.required' => 'Selecione um equipamento.',
            'equipment_id.exists' => 'O equipamento selecionado não existe.',

            'destination_branch_id.required' => 'Selecione a filial de destino.',
            'destination_branch_id.exists' => 'A filial de destino selecionada não existe.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'equipment_id' => 'equipamento',
            'destination_branch_id' => 'filial de destino',
            'observation' => 'observação',

        ];
    }
}
