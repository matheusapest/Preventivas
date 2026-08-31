<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\PreventiveProfileRuleType;
use App\Models\Branch;
use App\Models\OperationalUnit;
use App\Models\PreventiveProfile;
use App\Models\PreventiveProfileBranch;
use Illuminate\Support\Collection;

class ResolvePreventiveConfigurationService
{
    /**
     * Resolve a configuração efetiva de um perfil para uma filial.
     *
     * A resolução acontece neste momento, antes da criação da preventiva.
     *
     * Regras:
     *
     * - SPECIFIC:
     *   utiliza somente as unidades explicitamente vinculadas à regra.
     *
     * - ALL:
     *   utiliza todas as unidades elegíveis da filial,
     *   exceto aquelas que possuem uma regra SPECIFIC.
     *
     * Importante:
     *
     * A regra ALL não possui vínculo direto com unidades.
     * Portanto, suas unidades são determinadas pela diferença entre:
     *
     *   unidades elegíveis
     *   -
     *   unidades com regra específica
     *
     * Este serviço apenas resolve a configuração.
     * Ele não cria snapshot nem cycle.
     */
    public function execute(
        Branch $branch,
        PreventiveProfile $preventiveProfile
    ): array {
        /*
         * Garante que o tipo da preventiva esteja disponível.
         */
        $preventiveProfile->loadMissing([
            'preventiveType',
        ]);

        /*
         * Localiza a configuração do perfil para a filial.
         */
        $profileBranch = PreventiveProfileBranch::query()
            ->where('preventive_profile_id', $preventiveProfile->id)
            ->where('branch_id', $branch->id)
            ->with([
                'rules.units',
                'rules.activities.activity',
            ])
            ->firstOrFail();

        /*
         * O perfil precisa possuir um tipo de preventiva.
         */
        $preventiveType = $preventiveProfile->preventiveType;

        if (! $preventiveType) {
            abort(
                422,
                'O perfil de preventiva não possui um tipo configurado.'
            );
        }

        /*
         * ============================================================
         * UNIDADES ELEGÍVEIS
         * ============================================================
         *
         * Busca todas as unidades operacionais:
         *
         * - pertencentes à filial;
         * - pertencentes ao tipo de unidade do tipo de preventiva;
         * - ativas.
         *
         * Esta é a base utilizada para resolver a regra ALL.
         */
        $eligibleUnits = OperationalUnit::query()
            ->where('branch_id', $branch->id)
            ->where(
                'unit_type_id',
                $preventiveType->unit_type_id
            )
            ->where('active', true)
            ->orderBy('identifier')
            ->get([
                'id',
                'identifier',
                'branch_id',
                'unit_type_id',
                'operational_profile_id',
            ]);

        /*
         * ============================================================
         * UNIDADES COM REGRA SPECIFIC
         * ============================================================
         *
         * A regra ALL não possui unidades vinculadas.
         *
         * Portanto, precisamos descobrir quais unidades já foram
         * capturadas por alguma regra específica.
         */
        $specificUnitIds = $profileBranch->rules
            ->where(
                'rule_type',
                PreventiveProfileRuleType::SPECIFIC
            )
            ->flatMap(
                fn ($rule) => $rule->units
                    ->pluck('operational_unit_id')
            )
            ->unique()
            ->values();

        /*
         * ============================================================
         * UNIDADES DA REGRA ALL
         * ============================================================
         *
         * Todas as unidades elegíveis que NÃO possuem regra específica.
         *
         * Exemplo:
         *
         * Elegíveis:
         *   PDV 01
         *   PDV 02
         *   PDV 03
         *   PDV 04
         *
         * Specific:
         *   PDV 01
         *   PDV 03
         *
         * ALL:
         *   PDV 02
         *   PDV 04
         */
        $allUnits = $eligibleUnits
            ->whereNotIn('id', $specificUnitIds)
            ->values();

        /*
         * ============================================================
         * RESOLUÇÃO FINAL
         * ============================================================
         */
        $resolvedUnits = $this->resolveUnits(
            $eligibleUnits,
            $profileBranch->rules,
            $allUnits
        );

        /*
         * ============================================================
         * RETORNO
         * ============================================================
         *
         * O resultado abaixo representa a configuração efetiva
         * naquele momento.
         *
         * Posteriormente, o CreatePreventiveService poderá utilizar
         * este resultado para criar o snapshot imutável da preventiva.
         */
        return [
            'profile' => [
                'id' => $preventiveProfile->id,
                'name' => $preventiveProfile->name,
                'description' => $preventiveProfile->description,
            ],

            'branch' => [
                'id' => $branch->id,
                'name' => $branch->name,
            ],

            'preventive_type' => [
                'id' => $preventiveType->id,
                'name' => $preventiveType->name,
                'unit_type_id' => $preventiveType->unit_type_id,
            ],

            /*
             * Unidades efetivamente participantes.
             *
             * Cada unidade já vem com a regra que será aplicada.
             */
            'units' => $resolvedUnits,

            /*
             * Regras originais do perfil.
             *
             * Aqui permanecem as regras e suas atividades.
             *
             * A regra ALL continuará com units = [] porque ela
             * realmente não possui vínculos diretos na tabela.
             */
            'rules' => $profileBranch->rules,
        ];
    }

    /**
     * Monta as unidades efetivamente participantes da preventiva.
     *
     * Cada unidade recebe:
     *
     * - id
     * - identifier
     * - rule_type
     * - rule_id
     *
     * A resolução respeita a prioridade:
     *
     * SPECIFIC > ALL
     */
    private function resolveUnits(
        Collection $eligibleUnits,
        Collection $rules,
        Collection $allUnits
    ): Collection {
        $resolved = collect();

        /*
         * Percorre todas as regras configuradas no perfil.
         */
        foreach ($rules as $rule) {

            /*
             * ========================================================
             * REGRA SPECIFIC
             * ========================================================
             *
             * Somente as unidades explicitamente vinculadas
             * à regra participam dela.
             */
            if (
                $rule->rule_type ===
                PreventiveProfileRuleType::SPECIFIC
            ) {
                foreach ($rule->units as $ruleUnit) {

                    /*
                     * Localiza a unidade dentro das unidades elegíveis.
                     */
                    $unit = $eligibleUnits->firstWhere(
                        'id',
                        $ruleUnit->operational_unit_id
                    );

                    /*
                     * Se a unidade não estiver elegível,
                     * não deve participar da preventiva.
                     */
                    if (! $unit) {
                        continue;
                    }

                    $resolved->push([
                        'id' => $unit->id,
                        'identifier' => $unit->identifier,
                        'rule_type' => PreventiveProfileRuleType::SPECIFIC->value,
                        'rule_id' => $rule->id,
                    ]);
                }

                continue;
            }

            /*
             * ========================================================
             * REGRA ALL
             * ========================================================
             *
             * IMPORTANTE:
             *
             * Não usamos:
             *
             *     $rule->units
             *
             * porque uma regra ALL não possui unidades vinculadas.
             *
             * Utilizamos o conjunto previamente calculado em
             * $allUnits.
             */
            if (
                $rule->rule_type ===
                PreventiveProfileRuleType::ALL
            ) {
                foreach ($allUnits as $unit) {
                    $resolved->push([
                        'id' => $unit->id,
                        'identifier' => $unit->identifier,
                        'rule_type' => PreventiveProfileRuleType::ALL->value,
                        'rule_id' => $rule->id,
                    ]);
                }
            }
        }

        /*
         * ============================================================
         * GARANTIA DE UNICIDADE
         * ============================================================
         *
         * Uma unidade deve aparecer somente uma vez na configuração
         * final.
         *
         * Como SPECIFIC é removida de $allUnits antes da resolução,
         * normalmente não haverá conflito entre SPECIFIC e ALL.
         *
         * O unique('id') funciona como proteção adicional.
         */
        return $resolved
            ->unique('id')
            ->sortBy('identifier')
            ->values();
    }
}
