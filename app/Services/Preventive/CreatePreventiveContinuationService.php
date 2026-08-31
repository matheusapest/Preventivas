<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\CycleReviewStatusEnum;
use App\Enums\StatusCycleEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveCycle;
use App\Models\Preventive\PreventiveSnapshot;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreatePreventiveContinuationService
{
    /**
     * Cria um novo Cycle de continuidade para uma preventiva
     * que teve o Cycle anterior finalizado e reprovado.
     *
     * O novo Cycle utiliza exclusivamente os dados congelados
     * no snapshot original da preventiva.
     *
     * Nenhum novo snapshot é criado.
     */
    public function execute(
        Preventive $preventive,
        array $units
    ): PreventiveCycle {
        return DB::transaction(function () use (
            $preventive,
            $units
        ) {

            /*
             * ---------------------------------------------------------
             * 1. VALIDA A PREVENTIVA
             * ---------------------------------------------------------
             */

            $this->validatePreventive($preventive);

            /*
             * ---------------------------------------------------------
             * 2. LOCALIZA O CYCLE ATUAL
             * ---------------------------------------------------------
             */

            $previousCycle = $this->getCurrentCycle(
                $preventive
            );

            $this->validatePreviousCycle(
                $previousCycle
            );

            /*
             * ---------------------------------------------------------
             * 3. VALIDA O ESCOPO RECEBIDO
             * ---------------------------------------------------------
             */

            $this->validateUnitsPayload($units);

            /*
             * ---------------------------------------------------------
             * 4. LOCALIZA O SNAPSHOT ORIGINAL
             * ---------------------------------------------------------
             *
             * Importante:
             *
             * Um novo Cycle da mesma preventiva NUNCA gera
             * um novo snapshot.
             *
             * Todos os dados utilizados abaixo pertencem ao
             * snapshot original da preventiva.
             */

            $snapshot = $this->getSnapshot(
                $preventive
            );

            /*
             * ---------------------------------------------------------
             * 5. INDEXA AS UNIDADES DO SNAPSHOT
             * ---------------------------------------------------------
             *
             * A chave utilizada pelo agregador é:
             *
             * operational_unit_id
             *
             * O ID da unidade real é utilizado apenas como
             * identificador de vínculo.
             *
             * Nome e identificador vêm exclusivamente do snapshot.
             */

            $snapshotUnits = $snapshot->units
                ->keyBy('operational_unit_id');

            /*
             * ---------------------------------------------------------
             * 6. INDEXA AS REGRAS DO SNAPSHOT
             * ---------------------------------------------------------
             */

            $ruleByOperationalUnit = [];

            foreach ($snapshot->rules as $snapshotRule) {

                foreach ($snapshotRule->units as $snapshotRuleUnit) {

                    $operationalUnitId =
                        $snapshotRuleUnit->operational_unit_id;

                    /*
                     * Se uma unidade aparecer novamente no snapshot,
                     * não sobrescrevemos silenciosamente a regra.
                     */
                    if (
                        isset(
                            $ruleByOperationalUnit[
                                $operationalUnitId
                            ]
                        )
                    ) {
                        throw ValidationException::withMessages([
                            'units' =>
                                sprintf(
                                    'A unidade "%s" possui mais de uma regra congelada no snapshot.',
                                    $snapshotRuleUnit
                                        ->operational_unit_identifier
                                ),
                        ]);
                    }

                    $ruleByOperationalUnit[
                        $operationalUnitId
                    ] = $snapshotRule;
                }
            }

            /*
             * ---------------------------------------------------------
             * 7. DETERMINA A NOVA SEQUÊNCIA
             * ---------------------------------------------------------
             */

            $nextSequence = (
                $preventive->cycles()->max('sequence') ?? 0
            ) + 1;

            /*
             * ---------------------------------------------------------
             * 8. CRIA O NOVO CYCLE
             * ---------------------------------------------------------
             */

            $cycle = $preventive->cycles()->create([
                'sequence' => $nextSequence,
                'status' => StatusCycleEnum::NEW,
            ]);

            $cycle->review_status =
                CycleReviewStatusEnum::PENDING;

            $cycle->save();

            /*
             * ---------------------------------------------------------
             * 9. CONTROLE DE DUPLICIDADE
             * ---------------------------------------------------------
             */

            $usedOperationalUnits = [];

            /*
             * ---------------------------------------------------------
             * 10. PROCESSA AS UNIDADES SELECIONADAS
             * ---------------------------------------------------------
             */

            foreach ($units as $unitData) {

                $operationalUnitId =
                    $unitData['operational_unit_id'] ?? null;

                $activityIds =
                    $unitData['activities'] ?? null;

                /*
                 * -----------------------------------------------------
                 * IDENTIFICAÇÃO DA UNIDADE
                 * -----------------------------------------------------
                 */

                if (
                    ! is_numeric($operationalUnitId)
                    || (int) $operationalUnitId <= 0
                ) {
                    throw ValidationException::withMessages([
                        'units' =>
                            'Uma das unidades selecionadas possui uma identificação inválida.',
                    ]);
                }

                $operationalUnitId =
                    (int) $operationalUnitId;

                /*
                 * -----------------------------------------------------
                 * DUPLICIDADE DE UNIDADE
                 * -----------------------------------------------------
                 */

                if (
                    isset(
                        $usedOperationalUnits[
                            $operationalUnitId
                        ]
                    )
                ) {
                    throw ValidationException::withMessages([
                        'units' =>
                            sprintf(
                                'A unidade "%s" foi selecionada mais de uma vez.',
                                $operationalUnitId
                            ),
                    ]);
                }

                $usedOperationalUnits[
                    $operationalUnitId
                ] = true;

                /*
                 * -----------------------------------------------------
                 * ATIVIDADES OBRIGATÓRIAS
                 * -----------------------------------------------------
                 *
                 * O novo agregador sempre envia as atividades
                 * explicitamente selecionadas.
                 */

                if (
                    ! is_array($activityIds)
                    || count($activityIds) === 0
                ) {
                    throw ValidationException::withMessages([
                        'units' =>
                            sprintf(
                                'Selecione pelo menos uma atividade para a unidade "%s".',
                                $operationalUnitId
                            ),
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * UNIDADE NO SNAPSHOT
                 * -----------------------------------------------------
                 */

                $snapshotUnit =
                    $snapshotUnits->get(
                        $operationalUnitId
                    );

                if (! $snapshotUnit) {
                    throw ValidationException::withMessages([
                        'units' =>
                            sprintf(
                                'A unidade operacional "%s" não pertence ao snapshot da preventiva.',
                                $operationalUnitId
                            ),
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * REGRA DA UNIDADE NO SNAPSHOT
                 * -----------------------------------------------------
                 */

                $snapshotRule =
                    $ruleByOperationalUnit[
                        $operationalUnitId
                    ] ?? null;

                if (! $snapshotRule) {
                    throw ValidationException::withMessages([
                        'units' =>
                            sprintf(
                                'A unidade "%s" não possui uma regra congelada.',
                                $snapshotUnit
                                    ->operational_unit_identifier
                            ),
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * NORMALIZA OS IDS DAS ATIVIDADES
                 * -----------------------------------------------------
                 */

                $normalizedActivityIds =
                    array_map(
                        'intval',
                        $activityIds
                    );

                /*
                 * -----------------------------------------------------
                 * DUPLICIDADE DE ATIVIDADES
                 * -----------------------------------------------------
                 */

                if (
                    count(
                        $normalizedActivityIds
                    ) !== count(
                        array_unique(
                            $normalizedActivityIds
                        )
                    )
                ) {
                    throw ValidationException::withMessages([
                        'units' =>
                            sprintf(
                                'Uma atividade foi selecionada mais de uma vez para a unidade "%s".',
                                $snapshotUnit
                                    ->operational_unit_identifier
                            ),
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * IDS INVÁLIDOS
                 * -----------------------------------------------------
                 */

                foreach ($normalizedActivityIds as $activityId) {

                    if ($activityId <= 0) {
                        throw ValidationException::withMessages([
                            'units' =>
                                sprintf(
                                    'Uma das atividades selecionadas para a unidade "%s" é inválida.',
                                    $snapshotUnit
                                        ->operational_unit_identifier
                                ),
                        ]);
                    }
                }

                /*
                 * -----------------------------------------------------
                 * ATIVIDADES DA REGRA CONGELADA
                 * -----------------------------------------------------
                 */

                $activities =
                    $snapshotRule->activities
                        ->whereIn(
                            'id',
                            $normalizedActivityIds
                        );

                /*
                 * -----------------------------------------------------
                 * GARANTE QUE TODAS AS ATIVIDADES PERTENCEM
                 * À REGRA DA UNIDADE
                 * -----------------------------------------------------
                 */

                if (
                    $activities->count() !==
                    count($normalizedActivityIds)
                ) {
                    throw ValidationException::withMessages([
                        'units' =>
                            sprintf(
                                'Uma ou mais atividades selecionadas não pertencem à unidade "%s" no snapshot da preventiva.',
                                $snapshotUnit
                                    ->operational_unit_identifier
                            ),
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * 11. CRIA A CYCLE UNIT
                 * -----------------------------------------------------
                 */

                $cycleUnit = $cycle->units()->create([
                    'snapshot_unit_id' =>
                        $snapshotUnit->id,

                    'operational_unit_id' =>
                        $snapshotUnit->operational_unit_id,
                ]);

                /*
                 * -----------------------------------------------------
                 * 12. CRIA AS ATIVIDADES DA CYCLE UNIT
                 * -----------------------------------------------------
                 */

                foreach ($activities as $snapshotActivity) {

                    $cycleUnit->activities()->create([
                        'snapshot_rule_activity_id' =>
                            $snapshotActivity->id,
                    ]);
                }
            }

            /*
             * ---------------------------------------------------------
             * 13. GARANTE QUE EXISTE PELO MENOS UMA UNIDADE
             * ---------------------------------------------------------
             */

            if ($usedOperationalUnits === []) {

                throw ValidationException::withMessages([
                    'units' =>
                        'Selecione pelo menos uma unidade para iniciar a continuidade.',
                ]);
            }

            /*
             * ---------------------------------------------------------
             * 14. ATUALIZA A PREVENTIVA
             * ---------------------------------------------------------
             */

            $preventive->current_cycle =
                $nextSequence;

            $preventive->status =
                StatusPreventiveEnum::IN_PROGRESS;

            $preventive->save();

            /*
             * ---------------------------------------------------------
             * 15. RETORNA O NOVO CYCLE
             * ---------------------------------------------------------
             */

            return $cycle->load([
                'units.snapshotUnit',
                'units.activities.snapshotRuleActivity',
            ]);
        });
    }

    /**
     * Garante que a preventiva está em execução.
     */
    private function validatePreventive(
        Preventive $preventive
    ): void {
        if (
            $preventive->status !==
            StatusPreventiveEnum::IN_PROGRESS
        ) {
            throw ValidationException::withMessages([
                'preventive' =>
                    'A preventiva não está disponível para continuidade.',
            ]);
        }
    }

    /**
     * Localiza o Cycle atualmente associado à preventiva.
     */
    private function getCurrentCycle(
        Preventive $preventive
    ): PreventiveCycle {
        $cycle = $preventive->cycles()
            ->where(
                'sequence',
                $preventive->current_cycle
            )
            ->first();

        if (! $cycle) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo atual da preventiva não foi encontrado.',
            ]);
        }

        return $cycle;
    }

    /**
     * Garante que o Cycle atual está finalizado e rejeitado.
     */
    private function validatePreviousCycle(
        PreventiveCycle $cycle
    ): void {
        if (
            $cycle->status !==
            StatusCycleEnum::FINISHED
        ) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo anterior ainda não foi finalizado.',
            ]);
        }

        if (
            $cycle->review_status !==
            CycleReviewStatusEnum::REJECTED
        ) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo anterior não está reprovado para continuidade.',
            ]);
        }
    }

    /**
     * Valida a estrutura básica recebida do agregador.
     */
    private function validateUnitsPayload(
        array $units
    ): void {
        if ($units === []) {
            throw ValidationException::withMessages([
                'units' =>
                    'Selecione pelo menos uma unidade para iniciar a continuidade.',
            ]);
        }
    }

    /**
     * Localiza o snapshot congelado da preventiva.
     */
    private function getSnapshot(
        Preventive $preventive
    ): PreventiveSnapshot {
        $snapshot = PreventiveSnapshot::query()
            ->where(
                'preventive_id',
                $preventive->id
            )
            ->with([
                'units',
                'rules.units',
                'rules.activities',
            ])
            ->first();

        if (! $snapshot) {
            throw ValidationException::withMessages([
                'snapshot' =>
                    'A preventiva não possui um snapshot de configuração.',
            ]);
        }

        return $snapshot;
    }
}
