<?php

namespace App\Http\Requests\Equipment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipmentModelRequest extends FormRequest
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

            'manufacturer_id' => [
                'required',
                'integer',
                'exists:manufacturers,id',
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('models')
                    ->where(fn ($query) => $query->where(
                        'manufacturer_id',
                        $this->manufacturer_id
                    ))
                    ->ignore($this->equipmentModel),
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

            'manufacturer_id.required' => 'Selecione o fabricante.',
            'manufacturer_id.exists' => 'O fabricante selecionado é inválido.',

            'category_id.required' => 'Selecione a categoria.',
            'category_id.exists' => 'A categoria selecionada é inválida.',

            'name.required' => 'Informe o nome do modelo.',
            'name.unique' => 'Já existe um modelo cadastrado para este fabricante.',

        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'manufacturer_id' => 'fabricante',
            'category_id' => 'categoria',
            'name' => 'modelo',
            'active' => 'status',

        ];
    }
}
