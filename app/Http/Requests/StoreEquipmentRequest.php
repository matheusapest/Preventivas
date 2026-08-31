<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreEquipmentRequest extends FormRequest
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
            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],
            'equipment_model_id' => [
                'required',
                'integer',
                'exists:models,id',
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:150'
            ],
            'asset_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('equipments', 'asset_number'),
            ],

            'serial_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('equipments', 'serial_number'),
            ],
            'internal_tag' => [
                'nullable',
                'string',
                'max:50',
            ],
            'description' => [
                'nullable',
                'string',
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
            'name.required' => 'Informe o nome do equipamento.',

            'asset_number.unique' =>
            'Já existe um equipamento cadastrado com este patrimônio.',

            'serial_number.unique' =>
            'Já existe um equipamento cadastrado com este número de série.',
        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [
            'branch_id' => 'filial',
            'equipment_model_id' => 'modelo',
            'name' => 'nome',
            'asset_number' => 'patrimônio',
            'serial_number' => 'número de série',
            'internal_tag' => 'etiqueta interna',
            'description' => 'observações',
            'active' => 'status',
            'operational_status'=> 'Status Operacional'
        ];
    }
}
