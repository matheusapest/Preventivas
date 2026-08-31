<?php

namespace App\Http\Requests\Preventive;

use Illuminate\Foundation\Http\FormRequest;

class StorePreventiveExecutionRequest extends FormRequest
{
    /**
     * Autoriza a requisição.
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
            'preventive_cycle_unit_id' => [
                'required',
                'integer',
                'exists:preventive_cycle_units,id',
            ],

            'started_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}
