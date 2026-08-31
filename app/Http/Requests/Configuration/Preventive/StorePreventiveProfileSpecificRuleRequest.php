<?php

declare(strict_types=1);

namespace App\Http\Requests\Configuration\Preventive;

use Illuminate\Foundation\Http\FormRequest;

class StorePreventiveProfileSpecificRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'operational_unit_id' => [
                'required',
                'integer',
                'exists:operational_units,id',
            ],

            'activity_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'activity_ids.*' => [
                'integer',
                'distinct',
                'exists:activities,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'operational_unit_id.required' =>
                'A unidade operacional é obrigatória.',

            'operational_unit_id.integer' =>
                'A unidade operacional selecionada é inválida.',

            'operational_unit_id.exists' =>
                'A unidade operacional selecionada não existe.',

            'activity_ids.required' =>
                'Pelo menos uma atividade deve ser selecionada.',

            'activity_ids.array' =>
                'As atividades informadas são inválidas.',

            'activity_ids.min' =>
                'Pelo menos uma atividade deve ser selecionada.',

            'activity_ids.*.integer' =>
                'Uma das atividades selecionadas é inválida.',

            'activity_ids.*.distinct' =>
                'Uma atividade não pode ser adicionada mais de uma vez.',

            'activity_ids.*.exists' =>
                'Uma das atividades selecionadas não existe.',
        ];
    }
}
