<?php

declare(strict_types=1);

namespace App\Services\Preventive\Configuration;

use App\Models\Branch;
use App\Models\Configuration\Preventive\PreventiveProfile;
use App\Models\Configuration\Preventive\PreventiveType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PreventiveProfileService
{
    /**
     * Cria um novo perfil de preventiva.
     *
     * Nesta etapa o perfil é composto por:
     * - dados básicos;
     * - tipo de preventiva;
     * - filiais participantes.
     *
     * As regras serão configuradas posteriormente
     * através do módulo específico de regras.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): PreventiveProfile
    {
        return DB::transaction(function () use ($data): PreventiveProfile {
            $this->validateBranchesForPreventiveType(
                $data['preventive_type_id'],
                $data['branch_ids']
            );

            $this->validatePreventiveTypeHasActivities(
                (int) $data['preventive_type_id']
            );

            $profile = PreventiveProfile::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'preventive_type_id' => $data['preventive_type_id'],
                'active' => $data['active'] ?? true,
            ]);

            $this->syncBranches(
                $profile,
                $data['branch_ids']
            );

            return $this->loadProfile($profile);
        });
    }

    /**
     * Atualiza um perfil de preventiva.
     *
     * O tipo de preventiva pode ser alterado enquanto o perfil
     * ainda não possuir regras configuradas.
     *
     * Após a criação da primeira regra, o tipo fica bloqueado
     * para preservar a integridade das regras existentes.
     *
     * @param array<string, mixed> $data
     */
    public function update(
        PreventiveProfile $profile,
        array $data
    ): PreventiveProfile {
        return DB::transaction(function () use ($profile, $data): PreventiveProfile {

            $currentPreventiveTypeId = (int) $profile->preventive_type_id;
            $newPreventiveTypeId = (int) $data['preventive_type_id'];

            /*
         * O tipo pode ser alterado somente enquanto o perfil
         * ainda não possuir regras configuradas.
         */
            if ($currentPreventiveTypeId !== $newPreventiveTypeId) {
                $hasRules = $profile->branches()
                    ->whereHas('rules')
                    ->exists();

                if ($hasRules) {
                    throw ValidationException::withMessages([
                        'preventive_type_id' =>
                        'Não é possível alterar o tipo de preventiva porque este perfil já possui regras configuradas.',
                    ]);
                }
            }

            /*
         * Valida as filiais de acordo com o tipo de preventiva.
         */
            $this->validateBranchesForPreventiveType(
                $newPreventiveTypeId,
                $data['branch_ids']
            );

            $this->validatePreventiveTypeHasActivities(
                (int) $data['preventive_type_id']
            );
            /*
         * Atualiza os dados do perfil.
         */
            $profile->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'preventive_type_id' => $newPreventiveTypeId,
                'active' => $data['active'] ?? $profile->active,
            ]);

            /*
         * Sincroniza as filiais participantes.
         */
            $this->syncBranches(
                $profile,
                $data['branch_ids']
            );

            return $this->loadProfile($profile);
        });
    }
    /**
     * Retorna as filiais elegíveis para um tipo de preventiva.
     *
     * Uma filial é elegível quando:
     * - está ativa;
     * - possui pelo menos uma unidade operacional ativa;
     * - a unidade operacional pertence ao tipo de unidade
     *   da preventiva.
     */
    public function getEligibleBranches(
        PreventiveType $preventiveType
    ): Collection {
        $unitTypeId = $preventiveType->unit_type_id;

        return Branch::query()
            ->active()
            ->whereHas('operationalUnits', function ($query) use ($unitTypeId) {
                $query
                    ->active()
                    ->where('unit_type_id', $unitTypeId);
            })
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);
    }

    /**
     * Garante que todas as filiais informadas possuem pelo menos
     * uma unidade operacional ativa compatível com o tipo da preventiva.
     *
     * @param int|string $preventiveTypeId
     * @param array<int, int|string> $branchIds
     */
    private function validateBranchesForPreventiveType(
        int|string $preventiveTypeId,
        array $branchIds
    ): void {
        $branchIds = array_values(
            array_unique(
                array_map('intval', $branchIds)
            )
        );

        if (empty($branchIds)) {
            throw ValidationException::withMessages([
                'branch_ids' => 'Selecione pelo menos uma filial.',
            ]);
        }

        $preventiveType = PreventiveType::query()
            ->whereKey($preventiveTypeId)
            ->where('active', true)
            ->first();

        if (!$preventiveType) {
            throw ValidationException::withMessages([
                'preventive_type_id' => 'O tipo de preventiva selecionado é inválido ou está inativo.',
            ]);
        }

        $eligibleBranchIds = $this->getEligibleBranches($preventiveType)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $invalidBranchIds = array_diff(
            $branchIds,
            $eligibleBranchIds
        );

        if (!empty($invalidBranchIds)) {
            throw ValidationException::withMessages([
                'branch_ids' => 'Uma ou mais filiais selecionadas não possuem unidade operacional ativa compatível com o tipo de preventiva.',
            ]);
        }
    }

    private function validatePreventiveTypeHasActivities(
        int $preventiveTypeId
    ): void {
        $preventiveType = PreventiveType::query()
            ->whereKey($preventiveTypeId)
            ->where('active', true)
            ->whereHas('activities', function ($query) {
                $query->where('active', true);
            })
            ->exists();

        if (!$preventiveType) {
            throw ValidationException::withMessages([
                'preventive_type_id' =>
                'O tipo de preventiva selecionado não possui atividades ativas configuradas.',
            ]);
        }
    }

    /**
     * Sincroniza as filiais participantes do perfil.
     *
     * @param array<int, int|string> $branchIds
     */
    private function syncBranches(
        PreventiveProfile $profile,
        array $branchIds
    ): void {
        $branchIds = array_values(
            array_unique(
                array_map('intval', $branchIds)
            )
        );

        /**
         * Filiais atualmente vinculadas ao perfil.
         */
        $currentBranchIds = $profile->branches()
            ->pluck('branch_id')
            ->map(fn($id) => (int) $id)
            ->all();

        /**
         * Remove as filiais que deixaram
         * de participar do perfil.
         */
        $branchesToRemove = array_diff(
            $currentBranchIds,
            $branchIds
        );

        if (!empty($branchesToRemove)) {
            $profile->branches()
                ->whereIn('branch_id', $branchesToRemove)
                ->delete();
        }

        /**
         * Cria somente as novas filiais.
         */
        $branchesToCreate = array_diff(
            $branchIds,
            $currentBranchIds
        );

        foreach ($branchesToCreate as $branchId) {
            $profile->branches()->create([
                'branch_id' => $branchId,
            ]);
        }
    }

    /**
     * Carrega o perfil com as relações necessárias
     * para apresentação.
     */
    private function loadProfile(
        PreventiveProfile $profile
    ): PreventiveProfile {
        return $profile->load([
            'preventiveType',
            'branches.branch',
        ]);
    }

    /**
     * Desativa um perfil de preventiva.
     *
     * Um perfil que já possui regras configuradas não pode ser
     * desativado, pois essas regras fazem parte do fluxo configurado.
     */
    public function deactivate(
        PreventiveProfile $profile
    ): PreventiveProfile {
        $hasRules = $profile->branches()
            ->whereHas('rules')
            ->exists();

        if ($hasRules) {
            throw ValidationException::withMessages([
                'profile' => 'Não é possível inativar este perfil porque existem regras configuradas para ele.',
            ]);
        }

        $profile->update([
            'active' => false,
        ]);

        return $profile->refresh();
    }

    /**
     * Ativa um perfil de preventiva.
     */
    public function activate(
        PreventiveProfile $profile
    ): PreventiveProfile {
        $profile->update([
            'active' => true,
        ]);

        return $profile->refresh();
    }
}
