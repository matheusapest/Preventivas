<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\PreventiveProfileRuleType;
use App\Models\OperationalUnit;
use App\Models\PreventiveProfile;
use App\Models\PreventiveProfileBranch;
use App\Models\PreventiveProfileRule;
use App\Services\PreventiveProfileRule\PreventiveProfileRuleCrudService;
use App\Services\PreventiveProfileRule\PreventiveProfileRuleFormDataService;
use App\Services\PreventiveProfileRule\PreventiveProfileRuleQueryService;
use App\Services\PreventiveProfileRule\PreventiveProfileRuleSpecificService;
use App\Services\PreventiveProfileRule\PreventiveProfileRuleValidationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Fachada de regras de perfil preventivo.
 *
 * Apenas delega para os services de módulo abaixo, mantendo a API
 * pública original para não quebrar quem já consome este service:
 *
 * - PreventiveProfileRuleQueryService      -> consultas/listagens
 * - PreventiveProfileRuleFormDataService   -> dados de formulário
 * - PreventiveProfileRuleValidationService -> validações
 * - PreventiveProfileRuleCrudService       -> CRUD da regra ALL
 * - PreventiveProfileRuleSpecificService   -> CRUD da regra SPECIFIC
 */
class PreventiveProfileRuleService
{
    public function __construct(
        private readonly PreventiveProfileRuleQueryService $queryService,
        private readonly PreventiveProfileRuleFormDataService $formDataService,
        private readonly PreventiveProfileRuleValidationService $validationService,
        private readonly PreventiveProfileRuleCrudService $crudService,
        private readonly PreventiveProfileRuleSpecificService $specificService,
    ) {}

    /**
     * Retorna as regras de um perfil.
     */
    public function getRules(
        PreventiveProfile $profile,
        array $filters = []
    ) {
        return $this->queryService->getRules($profile, $filters);
    }

    /**
     * Retorna as configurações de regras agrupadas por filial.
     */
    public function getBranchConfigurations(
        PreventiveProfile $profile,
        array $filters = []
    ) {
        return $this->queryService->getBranchConfigurations($profile, $filters);
    }

    /**
     * Retorna a quantidade de filiais com regra ALL configurada.
     */
    public function countConfiguredBranches(
        PreventiveProfile $profile
    ): int {
        return $this->queryService->countConfiguredBranches($profile);
    }

    /**
     * Retorna as unidades operacionais ativas da filial
     * que ainda não possuem uma regra específica.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, OperationalUnit>
     */
    public function getAvailableOperationalUnits(
        PreventiveProfileBranch $profileBranch,
        ?int $unitTypeId = null
    ) {
        return $this->queryService->getAvailableOperationalUnits(
            $profileBranch,
            $unitTypeId
        );
    }

    /**
     * Retorna os dados necessários para os formulários.
     */
    public function getFormData(
        PreventiveProfile $profile,
        ?PreventiveProfileRule $rule = null
    ): array {
        return $this->formDataService->getFormData($profile, $rule);
    }

    /**
     * Cria uma regra específica para uma unidade operacional.
     *
     * @param array<string, mixed> $data
     */
    public function createSpecificRule(
        PreventiveProfile $profile,
        PreventiveProfileRule $rule,
        array $data
    ): PreventiveProfileRule {
        return $this->specificService->createSpecificRule($profile, $rule, $data);
    }

    /**
     * Atualiza uma regra específica existente.
     *
     * @param array<string, mixed> $data
     */
    public function updateSpecificRule(
        PreventiveProfile $profile,
        PreventiveProfileRule $specificRule,
        array $data
    ): void {
        $this->specificService->updateSpecificRule($profile, $specificRule, $data);
    }

    /**
     * Deleta uma regra específica existente.
     */
    public function destroy(
        PreventiveProfileRule $specificRule
    ): void {
        if (
            $specificRule->rule_type
            !== PreventiveProfileRuleType::SPECIFIC
        ) {
            throw ValidationException::withMessages([
                'rule' => 'Apenas regras específicas podem ser excluídas por este método.',
            ]);
        }

        DB::transaction(function () use ($specificRule): void {
            $specificRule->units()->delete();
            $specificRule->activities()->delete();
            $specificRule->delete();
        });
    }


    /**
     * Atualiza uma regra (somente regra Todos).
     *
     * @param array<string, mixed> $data
     */
    public function update(
        PreventiveProfileRule $rule,
        array $data
    ): PreventiveProfileRule {
        return $this->crudService->update($rule, $data);
    }

    /**
     * Cria uma nova regra para o perfil de preventiva.
     *
     * @param array<string, mixed> $data
     */
    public function create(
        PreventiveProfile $profile,
        array $data
    ): PreventiveProfileRule {
        return $this->crudService->create($profile, $data);
    }

    /**
     * Remove uma regra e suas relações.
     */
    public function delete(
        PreventiveProfileRule $rule
    ): void {
        $this->crudService->delete($rule);
    }

    /**
     * Remove toda a configuração de uma filial.
     *
     * Remove a regra ALL e todas as regras específicas
     * pertencentes à filial.
     */
    public function deleteBranchConfiguration(
        PreventiveProfileBranch $profileBranch
    ): void {
        $this->crudService->deleteBranchConfiguration($profileBranch);
    }

    /**
     * Garante que uma regra pertence ao perfil informado.
     */
    public function validateRuleBelongsToProfile(
        PreventiveProfile $profile,
        PreventiveProfileRule $rule
    ): void {
        $this->validationService->validateRuleBelongsToProfile($profile, $rule);
    }

    /**
     * Retorna uma regra preparada para a tela de visualização.
     */
    public function getRuleForShow(
        PreventiveProfileRule $rule
    ): PreventiveProfileRule {
        return $this->queryService->getRuleForShow($rule);
    }

    /**
     * Retorna os dados gerais do resumo das regras do perfil.
     *
     * Os dados não são afetados pelos filtros da listagem.
     */
    public function getSummaryData(
        PreventiveProfile $profile
    ): array {
        return $this->queryService->getSummaryData($profile);
    }
}
