<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Models\OperationalUnit;
use App\Models\Preventive;
use App\Models\PreventiveSnapshot;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreatePreventiveSnapshotService
{
    public function __construct(
        private ResolvePreventiveConfigurationService $resolveConfiguration
    ) {}

    /**
     * Cria o snapshot imutável da preventiva.
     *
     * O snapshot representa a configuração da preventiva
     * exatamente no momento em que ela foi criada.
     *
     * Depois de criado, os ciclos nunca mais consultam
     * a configuração dinâmica do perfil.
     */
    public function execute(
        Preventive $preventive
    ): PreventiveSnapshot {
        return DB::transaction(function () use ($preventive) {

            /*
             * ---------------------------------------------------------
             * 1. GARANTE QUE A PREVENTIVA NÃO POSSUI SNAPSHOT
             * ---------------------------------------------------------
             */

            $existingSnapshot = PreventiveSnapshot::query()
                ->where('preventive_id', $preventive->id)
                ->first();

            if ($existingSnapshot) {
                throw ValidationException::withMessages([
                    'preventive' =>
                    'A preventiva já possui um snapshot de configuração.',
                ]);
            }

            /*
             * ---------------------------------------------------------
             * 2. CARREGA A CONFIGURAÇÃO ATUAL DO PERFIL
             * ---------------------------------------------------------
             *
             * Esta é a última vez em que consultamos a configuração
             * dinâmica do perfil para esta preventiva.
             */

            $preventive->load([
                'branch',
                'preventiveType',
                'preventiveProfile',
            ]);

            $configuration = $this->resolveConfiguration->execute(
                $preventive->branch,
                $preventive->preventiveProfile
            );

            /*
             * ---------------------------------------------------------
             * 3. CRIA O SNAPSHOT PRINCIPAL
             * ---------------------------------------------------------
             */

            $snapshot = PreventiveSnapshot::create([
                'preventive_id' =>
                $preventive->id,

                'preventive_type_id' =>
                $preventive->preventive_type_id,

                'preventive_profile_id' =>
                $preventive->preventive_profile_id,

                'branch_id' =>
                $preventive->branch_id,

                'preventive_type_name' =>
                $configuration['preventive_type']['name'],

                'preventive_profile_name' =>
                $configuration['profile']['name'],

                'branch_name' =>
                $configuration['branch']['name'],
            ]);

            /*
             * ---------------------------------------------------------
             * 4. CONGELA AS REGRAS
             * ---------------------------------------------------------
             */

            $snapshotRules = [];

            foreach ($configuration['rules'] as $rule) {

                $snapshotRule = $snapshot->rules()->create([
                    'preventive_profile_rule_id' =>
                    $rule->id,

                    'rule_type' =>
                    $rule->rule_type->value,
                ]);

                $snapshotRules[$rule->id] = $snapshotRule;

                /*
                 * -----------------------------------------------------
                 * 4.1 CONGELA AS ATIVIDADES DA REGRA
                 * -----------------------------------------------------
                 */

                foreach ($rule->activities as $ruleActivity) {

                    $activity = $ruleActivity->activity;

                    if (!$activity) {
                        throw ValidationException::withMessages([
                            'snapshot' =>
                            "A atividade vinculada à regra {$rule->id} não foi encontrada.",
                        ]);
                    }

                    $snapshotRule->activities()->create([
                        'activity_id' =>
                        $activity->id,

                        'activity_name' =>
                        $activity->name,

                        'activity_description' =>
                        $activity->description,

                        'activity_type' =>
                        $activity->type,
                    ]);
                }
            }

            /*
             * ---------------------------------------------------------
             * 5. CONGELA AS UNIDADES PARTICIPANTES
             * ---------------------------------------------------------
             */

            foreach ($configuration['units'] as $resolvedUnit) {

                /*
                 * Localiza a unidade operacional.
                 */

                $operationalUnit = $configuration['rules']
                    ->flatMap(
                        fn($rule) => $rule->units
                    )
                    ->map(
                        fn($ruleUnit) =>
                        $ruleUnit->operationalUnit
                    )
                    ->filter()
                    ->firstWhere(
                        'id',
                        $resolvedUnit['id']
                    );

                /*
                 * Para unidades provenientes da regra ALL,
                 * buscamos diretamente a unidade operacional.
                 */

                if (!$operationalUnit) {
                    $operationalUnit = OperationalUnit::query()
                        ->with([
                            'operationalProfile',
                            'unitType',
                        ])
                        ->find($resolvedUnit['id']);
                }

                if (!$operationalUnit) {
                    throw ValidationException::withMessages([
                        'snapshot' =>
                        "A unidade operacional {$resolvedUnit['id']} não foi encontrada.",
                    ]);
                }

                if (
                    !$operationalUnit->operationalProfile ||
                    !$operationalUnit->unitType
                ) {
                    throw ValidationException::withMessages([
                        'snapshot' =>
                        "A unidade {$operationalUnit->identifier} não possui perfil operacional ou tipo de unidade configurado.",
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * 5.1 CONGELA A COMPOSIÇÃO DO PERFIL OPERACIONAL
                 * -----------------------------------------------------
                 *
                 * A composição é copiada para o snapshot.
                 *
                 * Depois disso, alterações no perfil operacional
                 * original não afetam esta preventiva.
                 */

               $operationalProfileComposition = $operationalUnit
                    ->operationalProfile
                    ->categories()
                    ->with('category')
                    ->get()
                    ->map(function ($profileCategory) {

                        if (!$profileCategory->category) {
                            throw ValidationException::withMessages([
                                'snapshot' =>
                                "A categoria {$profileCategory->category_id} do perfil operacional não foi encontrada.",
                            ]);
                        }

                        return [
                            'category_id' => $profileCategory->category_id,
                            'name' => $profileCategory->category->name,
                            'quantity' => $profileCategory->quantity,
                        ];
                    })
                    ->values()
                    ->all();
                /*
                 * -----------------------------------------------------
                 * 5.2 CRIA A UNIDADE CONGELADA
                 * -----------------------------------------------------
                 */

                $snapshotUnit = $snapshot->units()->create([
                    'operational_unit_id' =>
                    $operationalUnit->id,

                    'operational_profile_id' =>
                    $operationalUnit->operational_profile_id,

                    'unit_type_id' =>
                    $operationalUnit->unit_type_id,

                    'operational_unit_name' =>
                    $operationalUnit->identifier,

                    'operational_unit_identifier' =>
                    $operationalUnit->identifier,

                    'operational_profile_name' =>
                    $operationalUnit->operationalProfile->name,

                    'unit_type_name' =>
                    $operationalUnit->unitType->name,

                    'operational_composition' => $operationalProfileComposition,
                ]);

                /*
                 * -----------------------------------------------------
                 * 5.3 CONGELA A RELAÇÃO UNIDADE → REGRA
                 * -----------------------------------------------------
                 */

                $profileRuleId = $resolvedUnit['rule_id'];

                $snapshotRule = $snapshotRules[$profileRuleId]
                    ?? null;

                if (!$snapshotRule) {
                    throw ValidationException::withMessages([
                        'snapshot' =>
                        "A regra {$profileRuleId} da unidade {$operationalUnit->identifier} não foi encontrada no snapshot.",
                    ]);
                }

                $snapshotRule->units()->create([
                    'operational_unit_id' =>
                    $operationalUnit->id,

                    'operational_unit_name' =>
                    $operationalUnit->identifier,

                    'operational_unit_identifier' =>
                    $operationalUnit->identifier,
                ]);
            }

            /*
             * ---------------------------------------------------------
             * 6. RETORNA O SNAPSHOT COMPLETO
             * ---------------------------------------------------------
             */

            return $snapshot->fresh([
                'units',
                'rules.units',
                'rules.activities',
            ]);
        });
    }
}
