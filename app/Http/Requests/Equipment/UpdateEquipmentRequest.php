<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\OperationalStatus;
use Illuminate\Validation\Rules\Enum;

class UpdateEquipmentRequest extends FormRequest
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
                'max:150',
            ],

            'asset_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('equipments', 'asset_number')
                    ->ignore($this->route('equipment')->id),
            ],
            'serial_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('equipments', 'serial_number')
                    ->ignore($this->route('equipment')->id),
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

            'operational_status' => [
                'required',
                new Enum(OperationalStatus::class),
            ],

        ];
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [
            'asset_number.unique' =>
            'Já existe um equipamento cadastrado com este patrimônio.',

            'serial_number.unique' =>
            'Já existe um equipamento cadastrado com este número de série.',

            'name.required' =>
            'Informe o nome do equipamento.',
            'operational_status.required' =>
            'Informe o status operacional do equipamento'


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

            'operational_status' => 'status operacional',

        ];
    }
}
