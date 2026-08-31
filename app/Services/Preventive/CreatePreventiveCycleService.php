<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\CycleReviewStatusEnum;
use App\Enums\StatusCycleEnum;
use App\Models\Preventive;
use App\Models\PreventiveCycle;
use App\Models\PreventiveSnapshot;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreatePreventiveCycleService
{
    /**
     * Cria um novo ciclo de execução utilizando
     * exclusivamente o snapshot congelado da preventiva.
     *
     * O ciclo nunca consulta novamente:
     *
     * - PreventiveProfile
     * - PreventiveProfileRule
     * - OperationalUnit
     * - ResolvePreventiveConfigurationService
     *
     * Toda a configuração utilizada pelo ciclo deve
     * vir do snapshot da preventiva.
     */
    public function execute(
        Preventive $preventive
    ): PreventiveCycle {
        return DB::transaction(function () use ($preventive) {

            /*
             * ---------------------------------------------------------
             * 1. LOCALIZA O SNAPSHOT
             * ---------------------------------------------------------
             *
             * Cada preventiva possui um único snapshot.
             *
             * O snapshot representa a configuração congelada
             * da preventiva no momento da sua criação.
             */

            $snapshot = PreventiveSnapshot::query()
                ->where('preventive_id', $preventive->id)
                ->with([
                    'units',
                    'rules.units',
                    'rules.activities',
                ])
                ->first();

            if (! $snapshot) {
                throw ValidationException::withMessages([
                    'preventive' =>
                    'A preventiva não possui um snapshot de configuração.',
                ]);
            }

            /*
             * ---------------------------------------------------------
             * 2. DETERMINA A SEQUÊNCIA DO CICLO
             * ---------------------------------------------------------
             *
             * Exemplo:
             *
             * nenhum ciclo → 1
             * ciclo 1      → 2
             * ciclo 1,2    → 3
             */

            $nextSequence = (
                $preventive->cycles()->max('sequence') ?? 0
            ) + 1;

            /*
             * ---------------------------------------------------------
             * 3. CRIA O CICLO
             * ---------------------------------------------------------
             *
             * Todo ciclo novo nasce:
             *
             * status        = NEW
             * review_status = PENDING
             *
             * O ciclo somente deverá passar para IN_PROGRESS
             * quando o técnico efetivamente iniciar a execução.
             *
             * A revisão somente será preenchida quando o gestor
             * analisar o ciclo finalizado.
             */

            $cycle = $preventive->cycles()->create([
                'sequence' => $nextSequence,
                'status' => StatusCycleEnum::NEW,
            ]);

            $cycle->review_status = CycleReviewStatusEnum::PENDING;
            $cycle->save();
            /*
             * ---------------------------------------------------------
             * 4. INDEXA A RELAÇÃO:
             * ---------------------------------------------------------
             *
             * unidade operacional → regra congelada
             *
             * Essa informação vem de:
             *
             * preventive_snapshot_rule_units
             *
             * Não devemos recalcular ALL/SPECIFIC aqui.
             *
             * Isso já foi resolvido quando o snapshot foi criado.
             */

            $ruleByOperationalUnit = $snapshot->rules
                ->flatMap(function ($snapshotRule) {
                    return $snapshotRule->units->map(
                        function ($snapshotRuleUnit) use ($snapshotRule) {
                            return [
                                'operational_unit_id' =>
                                $snapshotRuleUnit->operational_unit_id,

                                'snapshot_rule_id' =>
                                $snapshotRule->id,
                            ];
                        }
                    );
                })
                ->keyBy('operational_unit_id');

            /*
             * ---------------------------------------------------------
             * 5. CRIA AS UNIDADES DO CICLO
             * ---------------------------------------------------------
             */

            foreach ($snapshot->units as $snapshotUnit) {

                /*
                 * Descobre qual regra congelada pertence
                 * à unidade.
                 */

                $ruleReference = $ruleByOperationalUnit->get(
                    $snapshotUnit->operational_unit_id
                );

                /*
                 * Uma unidade existente no snapshot precisa
                 * obrigatoriamente possuir uma regra congelada.
                 *
                 * Se isso acontecer, o snapshot está inconsistente.
                 */

                if (! $ruleReference) {
                    throw ValidationException::withMessages([
                        'snapshot' =>
                        sprintf(
                            'A unidade "%s" não possui uma regra congelada no snapshot.',
                            $snapshotUnit->operational_unit_identifier
                        ),
                    ]);
                }

                /*
                 * Localiza a regra congelada.
                 */

                $snapshotRule = $snapshot->rules->firstWhere(
                    'id',
                    $ruleReference['snapshot_rule_id']
                );

                if (! $snapshotRule) {
                    throw ValidationException::withMessages([
                        'snapshot' =>
                        sprintf(
                            'A regra congelada da unidade "%s" não foi encontrada.',
                            $snapshotUnit->operational_unit_identifier
                        ),
                    ]);
                }

                /*
                 * -----------------------------------------------------
                 * CrIA A UNIDADE DENTRO DO CICLO
                 * -----------------------------------------------------
                 *
                 * preventive_cycle_units possui:
                 *
                 * - snapshot_unit_id
                 * - operational_unit_id
                 *
                 * O operational_unit_id é copiado do snapshot.
                 *
                 * Não consultamos OperationalUnit novamente.
                 */

                $cycleUnit = $cycle->units()->create([
                    'snapshot_unit_id' =>
                    $snapshotUnit->id,

                    'operational_unit_id' =>
                    $snapshotUnit->operational_unit_id,
                ]);

                /*
                 * -----------------------------------------------------
                 * 6. COPIA AS ATIVIDADES DA REGRA PARA O CICLO
                 * -----------------------------------------------------
                 *
                 * As atividades utilizadas são as atividades
                 * congeladas no snapshot.
                 *
                 * Não consultamos Activity novamente.
                 */

                foreach ($snapshotRule->activities as $snapshotActivity) {

                    $cycleUnit->activities()->create([
                        'snapshot_rule_activity_id' =>
                        $snapshotActivity->id,
                    ]);
                }
            }

            /*
             * ---------------------------------------------------------
             * 7. RETORNA O CICLO COMPLETO
             * ---------------------------------------------------------
             */

            return $cycle->load([
                'units.snapshotUnit',
                'units.activities.snapshotRuleActivity',
            ]);
        });
    }
}
