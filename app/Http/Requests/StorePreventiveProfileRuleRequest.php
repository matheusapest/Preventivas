<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\PreventiveProfileRuleType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreventiveProfileRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $preventiveProfile = $this->route('preventiveProfile');

        return [
            'preventive_profile_branch_id' => [
                'required',
                'integer',

                Rule::exists(
                    'preventive_profile_branches',
                    'id'
                )->where(
                    'preventive_profile_id',
                    $preventiveProfile->id
                ),
            ],

            'rule_type' => [
                'required',
                Rule::enum(PreventiveProfileRuleType::class),
            ],

            'operational_unit_ids' => [
                'nullable',
                'array',
            ],

            'operational_unit_ids.*' => [
                'integer',
                'distinct',
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

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $ruleType = $this->input('rule_type');

            /*
             * SPECIFIC precisa possuir pelo menos
             * uma unidade operacional.
             */
            if (
                $ruleType === PreventiveProfileRuleType::SPECIFIC->value
                && empty($this->input('operational_unit_ids', []))
            ) {
                $validator->errors()->add(
                    'operational_unit_ids',
                    'Uma regra específica deve possuir pelo menos uma unidade operacional.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'preventive_profile_branch_id.required' =>
                'A filial do perfil é obrigatória.',

            'preventive_profile_branch_id.integer' =>
                'A filial selecionada é inválida.',

            'preventive_profile_branch_id.exists' =>
                'A filial selecionada não pertence ao perfil de preventiva.',

            'rule_type.required' =>
                'O tipo da regra é obrigatório.',

            'rule_type.enum' =>
                'O tipo de regra selecionado é inválido.',

            'operational_unit_ids.array' =>
                'As unidades operacionais informadas são inválidas.',

            'operational_unit_ids.*.integer' =>
                'Uma das unidades operacionais selecionadas é inválida.',

            'operational_unit_ids.*.distinct' =>
                'Uma unidade operacional não pode ser adicionada mais de uma vez.',

            'operational_unit_ids.*.exists' =>
                'Uma das unidades operacionais selecionadas não existe.',

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
