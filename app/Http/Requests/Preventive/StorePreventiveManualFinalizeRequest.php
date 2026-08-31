<?php

declare(strict_types=1);

namespace App\Http\Requests\Preventive;

use Illuminate\Foundation\Http\FormRequest;

class StorePreventiveManualFinalizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'observation' => [
                'required',
                'string',
                'min:5',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'observation.required' =>
                'Informe o motivo para finalizar a preventiva com pendências.',

            'observation.min' =>
                'Informe um motivo com pelo menos 5 caracteres.',

            'observation.max' =>
                'O motivo não pode ultrapassar 5000 caracteres.',
        ];
    }
}
