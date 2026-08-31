<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\ActivityKind;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveCycleUnit;
use Illuminate\Validation\ValidationException;

class GetPreventiveActivityExecutionService
{
    /**
     * Retorna todos os dados necessários para a execução
     * de uma atividade dentro de uma unidade operacional.
     *
     * A execução utiliza exclusivamente os dados congelados
     * no ciclo da preventiva.
     */
    public function execute(
        Preventive $preventive,
        PreventiveCycleUnit $cycleUnit,
        int $activityId
    ): array {
        /*
         * ---------------------------------------------------------
         * 1. VALIDA O CICLO DA UNIDADE
         * ---------------------------------------------------------
         */

        $cycle = $cycleUnit->cycle;

        if (
            ! $cycle ||
            $cycle->preventive_id !== $preventive->id
        ) {
            throw ValidationException::withMessages([
                'cycleUnit' =>
                    'A unidade informada não pertence à preventiva.',
            ]);
        }

        /*
         * ---------------------------------------------------------
         * 2. CARREGA O SNAPSHOT DA UNIDADE
         * ---------------------------------------------------------
         */

        $cycleUnit->load([
            'snapshotUnit',
        ]);

        $snapshotUnit = $cycleUnit->snapshotUnit;

        if (! $snapshotUnit) {
            throw ValidationException::withMessages([
                'cycleUnit' =>
                    'O snapshot da unidade não foi encontrado.',
            ]);
        }

        /*
         * ---------------------------------------------------------
         * 3. LOCALIZA A ATIVIDADE NO CICLO
         * ---------------------------------------------------------
         */

        $cycleUnitActivity = $cycleUnit
            ->activities()
            ->with('snapshotRuleActivity')
            ->where(
                'snapshot_rule_activity_id',
                $activityId
            )
            ->first();

        if (! $cycleUnitActivity) {
            throw ValidationException::withMessages([
                'activity' =>
                    'A atividade informada não pertence à unidade.',
            ]);
        }

        /*
         * ---------------------------------------------------------
         * 4. SNAPSHOT DA ATIVIDADE
         * ---------------------------------------------------------
         */

        $snapshotRuleActivity =
            $cycleUnitActivity->snapshotRuleActivity;

        if (! $snapshotRuleActivity) {
            throw ValidationException::withMessages([
                'activity' =>
                    'O snapshot da atividade não foi encontrado.',
            ]);
        }

        /*
         * ---------------------------------------------------------
         * 5. COMPOSIÇÃO CONGELADA DA UNIDADE
         * ---------------------------------------------------------
         */

        $operationalComposition =
            $snapshotUnit->operational_composition ?? [];

        /*
         * Caso o Model ainda não possua cast para array.
         */
        if (is_string($operationalComposition)) {
            $operationalComposition = json_decode(
                $operationalComposition,
                true
            ) ?? [];
        }

        /*
         * Garante que sempre teremos um array.
         */
        if (! is_array($operationalComposition)) {
            $operationalComposition = [];
        }

        /*
         * ---------------------------------------------------------
         * 6. COMPONENTES PREPARADOS PARA A INTERFACE
         * ---------------------------------------------------------
         *
         * A composição original continua preservada em
         * $operationalComposition.
         *
         * Aqui transformamos a composição em uma lista linear
         * de componentes para o formulário.
         *
         * Exemplo:
         *
         * Impressora PDV - quantidade 2
         *
         * vira:
         *
         * Impressora PDV 1
         * Impressora PDV 2
         */

        $failedComponents = [];

        foreach ($operationalComposition as $component) {
            $categoryId = $component['category_id'] ?? null;

            $name = $component['name'] ?? 'Componente';

            $quantity = max(
                1,
                (int) ($component['quantity'] ?? 1)
            );

            for ($index = 1; $index <= $quantity; $index++) {
                $failedComponents[] = [
                    'category_id' => $categoryId,

                    'name' => $name,

                    'quantity_index' => $index,

                    'label' => $quantity > 1
                        ? "{$name} {$index}"
                        : $name,

                    'value' => json_encode([
                        'category_id' => $categoryId,
                        'name' => $name,
                        'quantity_index' => $index,
                    ], JSON_UNESCAPED_UNICODE),
                ];
            }
        }

        /*
         * ---------------------------------------------------------
         * 7. TIPO DA UNIDADE
         * ---------------------------------------------------------
         *
         * O nome vem diretamente do snapshot.
         *
         * Não consultamos a tabela unit_types porque a execução
         * deve utilizar a estrutura congelada da preventiva.
         */

        $unitTypeName = $snapshotUnit->unit_type_name;

        /*
         * Caso o snapshot possua uma relação de tipo,
         * podemos utilizá-la posteriormente.
         */

        if (
            ! $unitTypeName &&
            isset($snapshotUnit->unitType)
        ) {
            $unitTypeName =
                $snapshotUnit->unitType?->name;
        }

        /*
         * Fallback para evitar interface vazia.
         */

        $unitTypeName ??= 'Unidade operacional';

        /*
         * ---------------------------------------------------------
         * 8. PERFIL OPERACIONAL
         * ---------------------------------------------------------
         */

        $operationalProfileName =
            $snapshotUnit->operational_profile_name;

        /*
         * ---------------------------------------------------------
         * 9. TIPO DA ATIVIDADE
         * ---------------------------------------------------------
         */

        $activityKind = ActivityKind::from(
            $snapshotRuleActivity->activity_type
        );

        /*
         * ---------------------------------------------------------
         * 10. RETORNO DA EXECUÇÃO
         * ---------------------------------------------------------
         */

        return [
            'preventive' => $preventive,

            'cycle' => $cycle,

            'cycleUnit' => $cycleUnit,

            'snapshotUnit' => $snapshotUnit,

            'cycleUnitActivity' =>
                $cycleUnitActivity,

            'snapshotRuleActivity' =>
                $snapshotRuleActivity,

            'activityType' =>
                $activityKind,

            'activityTypeLabel' =>
                $activityKind->label(),

            'unitTypeName' =>
                $unitTypeName,

            'operationalProfileName' =>
                $operationalProfileName,

            'operationalComposition' =>
                $operationalComposition,

            'failedComponents' =>
                $failedComponents,
        ];
    }
}
