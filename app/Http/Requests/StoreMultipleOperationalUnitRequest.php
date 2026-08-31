<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultipleOperationalUnitRequest extends FormRequest
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
     *
     * No modo lista, o formulário envia os números
     * separados por vírgula. Aqui transformamos a string
     * em um array de inteiros.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('identifier_mode') !== 'list') {
            return;
        }

        $identifiers = $this->input('identifiers');

        if (!is_string($identifiers)) {
            return;
        }

        $identifiers = array_map(
            'trim',
            explode(',', $identifiers)
        );

        $identifiers = array_filter(
            $identifiers,
            fn($value) => $value !== ''
        );

        $identifiers = array_map(
            'intval',
            $identifiers
        );

        $this->merge([
            'identifiers' => array_values($identifiers),
        ]);
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

            'unit_type_id' => [
                'required',
                'integer',
                'exists:unit_types,id',
            ],

            'operational_profile_id' => [
                'required',
                'integer',
                'exists:operational_profiles,id',
            ],

            'prefix' => [
                'required',
                'string',
                'max:30',
            ],

            /*
             * Define como os identificadores serão informados:
             *
             * range = 01 até 40
             * list  = 01,02,03,07,10
             */
            'identifier_mode' => [
                'required',
                'in:range,list',
            ],

            /*
             * Utilizado quando identifier_mode = range.
             */
            'identifier_start' => [
                'required_if:identifier_mode,range',
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],

            'identifier_end' => [
                'required_if:identifier_mode,range',
                'nullable',
                'integer',
                'min:0',
                'max:9999',
                'gte:identifier_start',
            ],

            /*
             * Utilizado quando identifier_mode = list.
             */
            'identifiers' => [
                'required_if:identifier_mode,list',
                'nullable',
                'array',
                'min:1',
            ],

            'identifiers.*' => [
                'required',
                'integer',
                'distinct',
                'min:0',
                'max:9999',
            ],

            'active' => [
                'nullable',
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
            'branch_id.required' =>
            'A filial é obrigatória.',

            'branch_id.integer' =>
            'A filial selecionada é inválida.',

            'branch_id.exists' =>
            'A filial selecionada não existe.',

            'unit_type_id.required' =>
            'O tipo de unidade é obrigatório.',

            'unit_type_id.integer' =>
            'O tipo de unidade selecionado é inválido.',

            'unit_type_id.exists' =>
            'O tipo de unidade selecionado não existe.',

            'operational_profile_id.required' =>
            'O perfil operacional é obrigatório.',

            'operational_profile_id.integer' =>
            'O perfil operacional selecionado é inválido.',

            'operational_profile_id.exists' =>
            'O perfil operacional selecionado não existe.',

            'prefix.required' =>
            'O prefixo do lote é obrigatório.',

            'prefix.max' =>
            'O prefixo do lote não pode ter mais de 30 caracteres.',

            'identifier_mode.required' =>
            'Informe como os identificadores serão gerados.',

            'identifier_mode.in' =>
            'O modo de identificação do lote é inválido.',

            'identifier_start.required_if' =>
            'O identificador inicial é obrigatório para um intervalo.',

            'identifier_start.min' =>
            'O identificador inicial não pode ser negativo.',

            'identifier_start.max' =>
            'O identificador inicial não pode ser maior que 9999.',

            'identifier_end.required_if' =>
            'O identificador final é obrigatório para um intervalo.',

            'identifier_end.gte' =>
            'O identificador final deve ser maior ou igual ao inicial.',

            'identifier_end.min' =>
            'O identificador final não pode ser negativo.',

            'identifier_end.max' =>
            'O identificador final não pode ser maior que 9999.',

            'identifiers.required_if' =>
            'Informe os identificadores que deverão ser criados.',

            'identifiers.array' =>
            'A lista de identificadores é inválida.',

            'identifiers.min' =>
            'Informe pelo menos um identificador.',

            'identifiers.*.integer' =>
            'Um dos identificadores informados é inválido.',

            'identifiers.*.distinct' =>
            'Não é permitido informar o mesmo identificador mais de uma vez.',

            'identifiers.*.min' =>
            'Os identificadores não podem ser negativos.',

            'identifiers.*.max' =>
            'Os identificadores não podem ser maiores que 9999.',
        ];
    }

    /**
     * Nome amigável dos atributos.
     */
    public function attributes(): array
    {
        return [
            'branch_id' =>
            'filial',

            'unit_type_id' =>
            'tipo de unidade',

            'operational_profile_id' =>
            'perfil operacional',

            'prefix' =>
            'prefixo do lote',

            'identifier_mode' =>
            'modo de identificação',

            'identifier_start' =>
            'identificador inicial',

            'identifier_end' =>
            'identificador final',

            'identifiers' =>
            'identificadores',

            'active' =>
            'situação da unidade',
        ];
    }
}
