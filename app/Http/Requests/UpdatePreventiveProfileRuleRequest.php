<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreventiveProfileRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
