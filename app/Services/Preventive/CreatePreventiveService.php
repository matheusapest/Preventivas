<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive;
use App\Models\PreventiveProfile;
use App\Models\PreventiveProfileBranch;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreatePreventiveService
{
    public function __construct(
        private CreatePreventiveSnapshotService $createSnapshotService,
        private CreatePreventiveCycleService $createCycleService
    ) {}

    /**
     * Cria uma nova preventiva e sua estrutura inicial de execução.
     */
    public function execute(
        array $data,
        int $createdBy
    ): Preventive {
        return DB::transaction(function () use ($data, $createdBy) {

            /*
             * Validações estruturais da preventiva.
             */
            $this->validateProfile(
                $data['preventive_profile_id'],
                $data['preventive_type_id']
            );

            $this->validateBranch(
                $data['preventive_profile_id'],
                $data['branch_id']
            );

            /*
             * Cria a preventiva.
             */
            $preventive = Preventive::create([
                'branch_id' => $data['branch_id'],
                'preventive_type_id' => $data['preventive_type_id'],
                'preventive_profile_id' => $data['preventive_profile_id'],
                'assigned_user_id' => $data['assigned_user_id'],
                'created_by' => $createdBy,
                'start_date' => $data['start_date'],
                'due_date' => $data['due_date'] ?? null,
                'status' => StatusPreventiveEnum::NEW,
                'current_cycle' => 1,
            ]);

            /*
            * Primeiro congela a configuração.
            */
            $this->createSnapshotService->execute($preventive);

            /*
            * Depois cria o primeiro ciclo a partir
            * exclusivamente do snapshot.
            */
            $this->createCycleService->execute($preventive);



            return $preventive;
        });
    }

    private function validateProfile(
        int $profileId,
        int $preventiveTypeId
    ): void {
        $valid = PreventiveProfile::query()
            ->whereKey($profileId)
            ->where('preventive_type_id', $preventiveTypeId)
            ->exists();

        if (! $valid) {
            throw ValidationException::withMessages([
                'preventive_profile_id' =>
                'O perfil selecionado não pertence ao tipo de preventiva informado.',
            ]);
        }
    }

    private function validateBranch(
        int $profileId,
        int $branchId
    ): void {
        $valid = PreventiveProfileBranch::query()
            ->where('preventive_profile_id', $profileId)
            ->where('branch_id', $branchId)
            ->exists();

        if (! $valid) {
            throw ValidationException::withMessages([
                'branch_id' =>
                'A filial selecionada não está vinculada ao perfil informado.',
            ]);
        }
    }
}
