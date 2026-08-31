<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\OperationalProfile;
use App\Models\UnitType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreOperationalUnitRequest extends FormRequest
{
    /**
     * Determina se o usuário pode realizar a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras básicas de validação.
     */
    public function rules(): array
    {
        return [
            'identifier' => [
                'required',
                'string',
                'max:255',

                Rule::unique('operational_units', 'identifier')
                    ->where(function ($query) {
                        $query->where('branch_id', $this->input('branch_id'));
                    }),
            ],

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

            'active' => [
                'boolean',
            ],
        ];
    }

    /**
     * Valida as relações entre os dados.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $branchId = $this->input('branch_id');
                $unitTypeId = $this->input('unit_type_id');
                $profileId = $this->input('operational_profile_id');

                /*
                 * Se a filial não existe, a regra exists
                 * já será responsável pelo erro.
                 */
                if (! $branchId || ! $unitTypeId) {
                    return;
                }

                $branch = Branch::query()->find($branchId);

                $unitType = UnitType::query()->find($unitTypeId);

                if (! $branch || ! $unitType) {
                    return;
                }

                /*
                 * Verifica se o tipo de unidade está
                 * disponível para a filial selecionada.
                 */
                $typeAvailableForBranch = $branch->unitTypes()
                    ->whereKey($unitType->id)
                    ->exists();

                if (! $typeAvailableForBranch) {
                    $validator->errors()->add(
                        'unit_type_id',
                        'Este tipo de unidade não está disponível para a filial selecionada.'
                    );

                    return;
                }

                /*
                 * Se o perfil ainda não foi informado,
                 * não há o que validar nesta etapa.
                 */
                if (! $profileId) {
                    return;
                }

                $operationalProfile = OperationalProfile::query()
                    ->find($profileId);

                if (! $operationalProfile) {
                    return;
                }

                /*
                 * O perfil precisa pertencer ao tipo
                 * de unidade selecionado.
                 */
                if ((int) $operationalProfile->unit_type_id !== (int) $unitType->id) {
                    $validator->errors()->add(
                        'operational_profile_id',
                        'O perfil operacional selecionado não pertence ao tipo de unidade informado.'
                    );
                }

                /*
                 * O perfil precisa estar ativo.
                 */
                if (! $operationalProfile->active) {
                    $validator->errors()->add(
                        'operational_profile_id',
                        'O perfil operacional selecionado está inativo.'
                    );
                }

                /*
                 * O tipo de unidade precisa estar ativo.
                 */
                if (! $unitType->active) {
                    $validator->errors()->add(
                        'unit_type_id',
                        'O tipo de unidade selecionado está inativo.'
                    );
                }

                /*
                 * A filial precisa estar ativa.
                 */
                if (! $branch->active) {
                    $validator->errors()->add(
                        'branch_id',
                        'A filial selecionada está inativa.'
                    );
                }
            },
        ];
    }

    /**
     * Mensagens personalizadas.
     */
    public function messages(): array
    {
        return [
            'identifier.required' =>
                'Informe o identificador da unidade operacional.',

            'identifier.string' =>
                'O identificador deve ser um texto válido.',

            'identifier.max' =>
                'O identificador não pode possuir mais de 255 caracteres.',

            'identifier.unique' =>
                'Já existe uma unidade operacional com este identificador nesta filial.',

            'branch_id.required' =>
                'Selecione a filial.',

            'branch_id.integer' =>
                'A filial selecionada é inválida.',

            'branch_id.exists' =>
                'A filial selecionada não existe.',

            'unit_type_id.required' =>
                'Selecione o tipo de unidade.',

            'unit_type_id.integer' =>
                'O tipo de unidade selecionado é inválido.',

            'unit_type_id.exists' =>
                'O tipo de unidade selecionado não existe.',

            'operational_profile_id.required' =>
                'Selecione o perfil operacional.',

            'operational_profile_id.integer' =>
                'O perfil operacional selecionado é inválido.',

            'operational_profile_id.exists' =>
                'O perfil operacional selecionado não existe.',

            'active.boolean' =>
                'O status informado é inválido.',
        ];
    }
}
