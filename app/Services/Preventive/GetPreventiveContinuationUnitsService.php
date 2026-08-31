<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveSnapshot;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class GetPreventiveContinuationUnitsService
{
    /**
     * Retorna as unidades operacionais disponíveis para
     * serem adicionadas à continuidade da preventiva.
     *
     * Todos os dados apresentados ao gestor são provenientes
     * do snapshot congelado da preventiva.
     *
     * Nenhuma informação da configuração atual da unidade
     * operacional é consultada.
     */
    public function execute(
        Preventive $preventive,
        ?string $search = null
    ): Collection {
        $this->validatePreventive($preventive);

        $snapshot = $this->getSnapshot($preventive);

        $units = $snapshot->rules
            ->flatMap(
                fn ($rule) => $rule->units
            )
            ->unique('operational_unit_id')
            ->sortBy('operational_unit_identifier')
            ->values();

        /*
         * Aplica a busca somente sobre os dados
         * congelados no snapshot.
         */
        if (
            $search !== null &&
            trim($search) !== ''
        ) {
            $search = mb_strtolower(
                trim($search)
            );

            $units = $units
                ->filter(
                    function ($unit) use ($search): bool {
                        return str_contains(
                            mb_strtolower(
                                $unit->operational_unit_name ?? ''
                            ),
                            $search
                        )
                        ||
                        str_contains(
                            mb_strtolower(
                                $unit->operational_unit_identifier ?? ''
                            ),
                            $search
                        );
                    }
                )
                ->values();
        }

        return $units
            ->map(
                function ($unit): array {
                    return [
                        'operational_unit_id' =>
                            $unit->operational_unit_id,

                        'name' =>
                            $unit->operational_unit_name,

                        'identifier' =>
                            $unit->operational_unit_identifier,
                    ];
                }
            )
            ->values();
    }

    /**
     * A continuidade somente pode ser consultada
     * quando a preventiva estiver em execução.
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
