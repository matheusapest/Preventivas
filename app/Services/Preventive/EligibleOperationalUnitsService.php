<?php

declare(strict_types=1);

namespace App\Services\Preventive;

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
     * A regra "specific" utiliza as unidades explicitamente
     * vinculadas à regra.
     *
     * A regra "all" utiliza todas as unidades elegíveis
     * da filial para o tipo da preventiva, removendo as
     * unidades que possuem regras específicas.
     */
    public function execute(
        Branch $branch,
        PreventiveProfile $preventiveProfile
    ): array {
        $preventiveProfile->loadMissing([
            'preventiveType',
        ]);

        $profileBranch = PreventiveProfileBranch::query()
            ->where('preventive_profile_id', $preventiveProfile->id)
            ->where('branch_id', $branch->id)
            ->with([
                'rules.units',
                'rules.activities.activity',
            ])
            ->firstOrFail();

        $preventiveType = $preventiveProfile->preventiveType;

        if (!$preventiveType) {
            abort(
                422,
                'O perfil de preventiva não possui um tipo configurado.'
            );
        }

        /*
         * 1. Busca TODAS as unidades operacionais elegíveis
         * para a filial + tipo da preventiva.
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
         * 2. Busca os IDs das unidades que possuem
         * alguma regra específica.
         *
         * IMPORTANTE:
         * aqui NÃO procuramos unidades na regra ALL.
         * A regra ALL não possui unidades.
         */
        $specificUnitIds = $profileBranch->rules
            ->where('rule_type', 'specific')
            ->flatMap(
                fn ($rule) => $rule->units
                    ->pluck('operational_unit_id')
            )
            ->unique()
            ->values();

        /*
         * 3. A regra ALL representa:
         *
         * TODAS as unidades elegíveis
         *
         * MENOS
         *
         * as unidades que possuem regra específica.
         */
        $allUnits = $eligibleUnits
            ->whereNotIn('id', $specificUnitIds)
            ->values();

        /*
         * 4. Monta as unidades efetivamente resolvidas.
         */
        $resolvedUnits = $this->resolveUnits(
            $eligibleUnits,
            $profileBranch->rules,
            $allUnits
        );

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

            'units' => $resolvedUnits,

            'rules' => $profileBranch->rules,
        ];
    }

    /**
     * Resolve as unidades efetivamente participantes.
     */
    private function resolveUnits(
        Collection $eligibleUnits,
        Collection $rules,
        Collection $allUnits
    ): Collection {
        $resolved = collect();

        foreach ($rules as $rule) {

            /*
             * REGRA SPECIFIC
             *
             * Aqui sim usamos os vínculos gravados
             * em preventive_profile_rule_units.
             */
            if ($rule->rule_type === 'specific') {

                foreach ($rule->units as $ruleUnit) {

                    $unit = $eligibleUnits->firstWhere(
                        'id',
                        $ruleUnit->operational_unit_id
                    );

                    if (!$unit) {
                        continue;
                    }

                    $resolved->push([
                        'id' => $unit->id,
                        'identifier' => $unit->identifier,
                        'rule_type' => 'specific',
                        'rule_id' => $rule->id,
                    ]);
                }
            }

            /*
             * REGRA ALL
             *
             * NÃO usamos $rule->units.
             *
             * A unidade da regra ALL é determinada pela
             * diferença entre as unidades elegíveis e as
             * unidades que possuem regra específica.
             */
            if ($rule->rule_type === 'all') {

                foreach ($allUnits as $unit) {

                    $resolved->push([
                        'id' => $unit->id,
                        'identifier' => $unit->identifier,
                        'rule_type' => 'all',
                        'rule_id' => $rule->id,
                    ]);
                }
            }
        }

        return $resolved
            ->unique('id')
            ->sortBy('identifier')
            ->values();
    }
}
