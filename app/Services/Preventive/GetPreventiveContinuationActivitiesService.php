<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive;
use App\Models\PreventiveSnapshot;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class GetPreventiveContinuationActivitiesService
{
    /**
     * Retorna as atividades disponíveis para continuidade
     * de uma unidade operacional.
     *
     * IMPORTANTE:
     *
     * Toda a estrutura utilizada pertence ao snapshot
     * congelado da preventiva.
     *
     * Não consultamos:
     *
     * - Activity
     * - OperationalUnit
     * - PreventiveProfile
     * - PreventiveProfileRule
     *
     * A preventiva pode possuir vários Cycles, porém todos
     * continuam utilizando o mesmo snapshot original.
     */
    public function execute(
        Preventive $preventive,
        int $operationalUnitId
    ): array {
        $this->validatePreventive($preventive);

        $snapshot = $this->getSnapshot($preventive);

        $snapshotUnits = $snapshot->rules
            ->flatMap(
                fn ($rule) => $rule->units
            );

        $unit = $snapshotUnits
            ->firstWhere(
                'operational_unit_id',
                $operationalUnitId
            );

        if (! $unit) {
            throw ValidationException::withMessages([
                'operational_unit_id' =>
                    'A unidade selecionada não pertence à configuração congelada da preventiva.',
            ]);
        }

        /*
         * Localiza todas as regras congeladas que possuem
         * a unidade selecionada.
         *
         * Uma unidade pode estar relacionada a mais de uma
         * regra no snapshot.
         */
        $rules = $snapshot->rules
            ->filter(
                fn ($rule): bool =>
                    $rule->units->contains(
                        'operational_unit_id',
                        $operationalUnitId
                    )
            );

        /*
         * Consolida as atividades das regras encontradas.
         *
         * A atividade continua sendo exclusivamente aquela
         * congelada no snapshot.
         */
        $activities = $rules
            ->flatMap(
                fn ($rule) => $rule->activities
            )
            ->unique('id')
            ->values();

        return [
            'unit' => [
                'operational_unit_id' =>
                    $unit->operational_unit_id,

                'name' =>
                    $unit->operational_unit_name,

                'identifier' =>
                    $unit->operational_unit_identifier,
            ],

            'activities' =>
                $activities
                    ->map(
                        fn ($activity): array => [
                            'id' =>
                                $activity->id,

                            'name' =>
                                $activity->activity_name,

                            'description' =>
                                $activity->activity_description,

                            'type' =>
                                $activity->activity_type,
                        ]
                    )
                    ->values()
                    ->all(),
        ];
    }

    /**
     * A continuidade somente pode ser consultada quando
     * a preventiva estiver novamente em execução após
     * uma reprovação.
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
