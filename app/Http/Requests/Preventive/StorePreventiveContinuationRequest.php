<?php

declare(strict_types=1);

namespace App\Http\Requests\Preventive;

use Illuminate\Foundation\Http\FormRequest;

class StorePreventiveContinuationRequest extends FormRequest
{
    /**
     * Autoriza a tentativa de criação da continuidade.
     *
     * A autorização definitiva também será feita pela Policy
     * na Controller.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação da continuidade.
     */
    public function rules(): array
    {
        return [
            /*
             * ---------------------------------------------------------
             * UNIDADES
             * ---------------------------------------------------------
             */

            'units' => [
                'required',
                'array',
                'min:1',
            ],

            'units.*.operational_unit_id' => [
                'required',
                'integer',
                'min:1',
                'distinct',
            ],

            /*
             * ---------------------------------------------------------
             * ATIVIDADES
             * ---------------------------------------------------------
             */

            'units.*.activities' => [
                'required',
                'array',
                'min:1',
            ],

            'units.*.activities.*' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    /**
     * Mensagens de validação.
     */
    public function messages(): array
    {
        return [
            /*
             * ---------------------------------------------------------
             * UNIDADES
             * ---------------------------------------------------------
             */

            'units.required' =>
                'Selecione pelo menos uma unidade.',

            'units.array' =>
                'As unidades selecionadas são inválidas.',

            'units.min' =>
                'Selecione pelo menos uma unidade.',

            'units.*.operational_unit_id.required' =>
                'A unidade selecionada é inválida.',

            'units.*.operational_unit_id.integer' =>
                'A unidade selecionada é inválida.',

            'units.*.operational_unit_id.min' =>
                'A unidade selecionada é inválida.',

            'units.*.operational_unit_id.distinct' =>
                'Uma unidade foi selecionada mais de uma vez.',

            /*
             * ---------------------------------------------------------
             * ATIVIDADES
             * ---------------------------------------------------------
             */

            'units.*.activities.required' =>
                'Selecione pelo menos uma atividade para cada unidade.',

            'units.*.activities.array' =>
                'As atividades selecionadas são inválidas.',

            'units.*.activities.min' =>
                'Selecione pelo menos uma atividade para cada unidade.',

            'units.*.activities.*.required' =>
                'Uma atividade selecionada é inválida.',

            'units.*.activities.*.integer' =>
                'Uma atividade selecionada é inválida.',

            'units.*.activities.*.min' =>
                'Uma atividade selecionada é inválida.',
        ];
    }
}
