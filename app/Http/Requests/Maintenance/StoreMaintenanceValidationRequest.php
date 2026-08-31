<?php

declare(strict_types=1);

namespace App\Http\Requests\Maintenance;

use App\Enums\MaintenanceValidationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMaintenanceValidationRequest extends FormRequest
{
    /**
     * Determina se o usuário pode realizar esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepara os dados antes da validação.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'close_without_resend' =>
                $this->boolean('close_without_resend'),
        ]);
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [

            'validation_status' => [
                'required',
                Rule::enum(MaintenanceValidationStatus::class),
            ],

            'tests_performed' => [
                'required',
                'string',
                'min:3',
            ],

            'validation_observation' => [
                'nullable',
                'string',
            ],

            'close_without_resend' => [
                'boolean',
            ],

        ];
    }

    /**
     * Validações adicionais.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $status = $this->input('validation_status');

            $closeWithoutResend =
                $this->boolean('close_without_resend');

            /*
             * A opção de não reenviar somente pode ser utilizada
             * quando o reparo foi reprovado.
             */
            if (
                $closeWithoutResend &&
                $status !== MaintenanceValidationStatus::REJECTED->value
            ) {
                $validator->errors()->add(
                    'close_without_resend',
                    'A opção de não reenviar somente pode ser utilizada quando o reparo for reprovado.'
                );
            }

            /*
             * Quando o técnico decide não reenviar o equipamento,
             * ele precisa informar o motivo.
             */
            if (
                $closeWithoutResend &&
                blank($this->input('validation_observation'))
            ) {
                $validator->errors()->add(
                    'validation_observation',
                    'Informe o motivo pelo qual o equipamento não será reenviado.'
                );
            }
        });
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [

            'validation_status.required' =>
                'Informe o resultado da validação.',

            'validation_status.enum' =>
                'O resultado da validação informado é inválido.',

            'tests_performed.required' =>
                'Informe os testes realizados no equipamento.',

            'tests_performed.min' =>
                'A descrição dos testes realizados deve possuir pelo menos 3 caracteres.',

            'close_without_resend.boolean' =>
                'A decisão de não reenviar informada é inválida.',
        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [

            'validation_status' =>
                'resultado da validação',

            'tests_performed' =>
                'testes realizados',

            'validation_observation' =>
                'observação da validação',

            'close_without_resend' =>
                'decisão de não reenviar',
        ];
    }
}
