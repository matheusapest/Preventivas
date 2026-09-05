<?php

declare(strict_types=1);

namespace App\Services\Preventive\Creation;

use App\Enums\PreventiveProfileRuleType;
use App\Enums\StatusPreventiveEnum;
use App\Models\Configuration\Preventive\PreventiveProfile;
use App\Models\Configuration\Preventive\PreventiveProfileBranch;
use App\Models\Preventive\Preventive;
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

            /**
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

            /**
             * A filial precisa possuir uma regra ALL
             * configurada para o perfil selecionado.
             *
             * Essa regra garante que a preventiva tenha
             * unidades operacionais elegíveis.
             */
            $this->validateAllRule(
                $data['preventive_profile_id'],
                $data['branch_id']
            );

            /**
             * Impede a criação de uma nova preventiva
             * para a mesma filial e perfil enquanto
             * existir outra ainda não finalizada.
             */
            $this->validateNoOpenPreventive(
                $data['preventive_profile_id'],
                $data['branch_id']
            );

            /**
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

            /**
             * Primeiro congela a configuração.
             */
            $this->createSnapshotService->execute($preventive);

            /**
             * Depois cria o primeiro ciclo a partir
             * exclusivamente do snapshot.
             */
            $this->createCycleService->execute($preventive);

            return $preventive;
        });
    }

    /**
     * Valida se o perfil pertence ao tipo de preventiva informado.
     */
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

    /**
     * Valida se a filial está vinculada ao perfil.
     */
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

    /**
     * Valida se existe uma regra ALL para o perfil na filial.
     *
     * A regra ALL representa a configuração necessária
     * para identificar todas as unidades operacionais
     * elegíveis daquela filial.
     */
    private function validateAllRule(
        int $profileId,
        int $branchId
    ): void {
        $valid = PreventiveProfileBranch::query()
            ->where('preventive_profile_id', $profileId)
            ->where('branch_id', $branchId)
            ->whereHas('rules', function ($query) {
                $query->where(
                    'rule_type',
                    PreventiveProfileRuleType::ALL->value
                );
            })
            ->exists();

        if (! $valid) {
            throw ValidationException::withMessages([
                'preventive_profile_id' =>
                    'O perfil selecionado não possui uma regra para todas as unidades da filial.',
            ]);
        }
    }

    /**
     * Impede a criação de uma nova preventiva para a mesma
     * filial e perfil enquanto existir uma preventiva ainda
     * não finalizada.
     *
     * Uma preventiva é considerada finalizada quando
     * estiver com status APPROVED.
     */
    private function validateNoOpenPreventive(
        int $profileId,
        int $branchId
    ): void {
        $exists = Preventive::query()
            ->where('branch_id', $branchId)
            ->where('preventive_profile_id', $profileId)
            ->where(
                'status',
                '!=',
                StatusPreventiveEnum::APPROVED->value
            )
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'preventive_profile_id' =>
                    'Já existe uma preventiva não finalizada para esta filial e este perfil.',
            ]);
        }
    }
}
