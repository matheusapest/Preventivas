<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ActivityKind;
use App\Models\PreventiveSnapshotRuleActivity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreventiveActivityResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return match ($this->activityType()) {
            ActivityKind::OPERATIONAL_COMPOSITION =>
                $this->operationalCompositionRules(),

            ActivityKind::PHOTO =>
                $this->photoRules(),

            ActivityKind::TEXT =>
                $this->textRules(),

            ActivityKind::NUMBER =>
                $this->numberRules(),

            ActivityKind::BOOLEAN =>
                $this->booleanRules(),
        };
    }

    /**
     * Retorna o tipo da atividade a partir do snapshot.
     */
    private function activityType(): ActivityKind
    {
        $activityId = (int) $this->route('activity');

        $activity = PreventiveSnapshotRuleActivity::findOrFail(
            $activityId
        );

        return ActivityKind::from(
            $activity->activity_type
        );
    }

    /**
     * Regras exclusivas das atividades de composição operacional.
     *
     * Somente este tipo de atividade trabalha com:
     * - operational_status
     * - final_status
     * - failed_components
     */
    private function operationalCompositionRules(): array
    {
        return [
            'operational_status' => [
                'required',
                'string',
                Rule::in([
                    'yes',
                    'no',
                ]),
            ],

            'final_status' => [
                'nullable',
                'string',
                Rule::requiredIf(
                    fn (): bool =>
                        $this->input('operational_status') === 'no'
                ),
                Rule::in([
                    'resolvido',
                    'pendente',
                ]),
            ],

            'failed_components' => [
                'nullable',
                'array',
                'required_if:operational_status,no',
            ],

            'failed_components.*' => [
                'required',
                'json',
            ],

            'observation' => [
                'nullable',
                'string',
                'max:5000',
                'required_if:operational_status,no',
            ],
        ];
    }

    /**
     * Regras para atividades fotográficas.
     *
     * A foto é o dado específico da atividade.
     * A observação é opcional e representa o contexto
     * informado pelo técnico.
     *
     * Não existem result ou final_status neste fluxo.
     */
    private function photoRules(): array
    {
        return [
            'photo' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'observation' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    /**
     * Regras para atividades de texto.
     */
    private function textRules(): array
    {
        return [
            'response' => [
                'required',
                'string',
                'max:5000',
            ],

            'observation' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    /**
     * Regras para atividades numéricas.
     */
    private function numberRules(): array
    {
        return [
            'response' => [
                'required',
                'numeric',
            ],

            'observation' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    /**
     * Regras para atividades booleanas.
     */
    private function booleanRules(): array
    {
        return [
            'response' => [
                'required',
                'boolean',
            ],

            'observation' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            /*
             * Composição operacional
             */

            'operational_status.required' =>
                'Informe se o conjunto da unidade está operacional.',

            'operational_status.in' =>
                'A situação operacional informada é inválida.',

            'final_status.required' =>
                'Informe se a não conformidade foi resolvida ou permanece pendente.',

            'final_status.in' =>
                'A situação final informada é inválida.',

            'failed_components.required_if' =>
                'Informe os componentes que apresentam problema.',

            'failed_components.array' =>
                'Os componentes com problema devem ser enviados em formato válido.',

            'failed_components.*.json' =>
                'Um dos componentes possui dados inválidos.',

            /*
             * Foto
             */

            'photo.required' =>
                'Capture uma foto para concluir a atividade.',

            'photo.file' =>
                'O arquivo enviado não é válido.',

            'photo.image' =>
                'O arquivo enviado deve ser uma imagem.',

            'photo.mimes' =>
                'A foto deve estar em formato JPG, JPEG, PNG ou WEBP.',

            'photo.max' =>
                'A foto não pode ultrapassar 10 MB.',

            /*
             * Texto
             */

            'response.required' =>
                'Informe uma resposta para a atividade.',

            'response.string' =>
                'A resposta informada deve ser um texto.',

            'response.max' =>
                'A resposta não pode ultrapassar 5000 caracteres.',

            /*
             * Número
             */

            'response.numeric' =>
                'A resposta deve ser um número.',

            /*
             * Booleano
             */

            'response.boolean' =>
                'A resposta deve ser válida.',

            /*
             * Observação
             */

            'observation.required_if' =>
                'Descreva como a não conformidade foi resolvida ou por que permanece pendente.',

            'observation.string' =>
                'A observação informada é inválida.',

            'observation.max' =>
                'A observação não pode ultrapassar 5000 caracteres.',
        ];
    }
}
