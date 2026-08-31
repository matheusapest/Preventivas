<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StorePreventiveProfileRuleRequest;
use App\Http\Requests\UpdatePreventiveProfileRuleRequest;
use App\Http\Requests\StorePreventiveProfileSpecificRuleRequest;
use App\Http\Requests\UpdatePreventiveProfileSpecificRuleRequest;
use App\Models\PreventiveProfile;
use App\Models\PreventiveProfileRule;
use App\Models\PreventiveProfileBranch;
use App\Enums\PreventiveProfileRuleType;
use App\Services\PreventiveProfileRuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\PreventiveProfileRule\PreventiveProfileRuleQueryService;
use Illuminate\Http\Request;

class PreventiveProfileRuleController extends Controller
{
    public function __construct(
        private readonly PreventiveProfileRuleService $service,
        private readonly PreventiveProfileRuleQueryService $queryService
    ) {}

    /**
     * Exibe as regras configuradas para um perfil de preventiva.
     */
    public function index(
        PreventiveProfile $preventiveProfile
    ): View {
        $this->authorize(
            'view',
            $preventiveProfile
        );

        $preventiveProfile->load([
            'preventiveType',
            'branches.branch',
        ]);

        /**
         * ---------------------------------------------------------
         * RESUMO
         * ---------------------------------------------------------
         *
         * O resumo é independente dos filtros da listagem.
         */
        $summary = $this->service->getSummaryData(
            $preventiveProfile
        );

        /**
         * ---------------------------------------------------------
         * FILTROS
         * ---------------------------------------------------------
         */
        $filters = [
            'branch_id' => request('branch_id'),
            'status' => request('status'),
        ];

        /**
         * ---------------------------------------------------------
         * LISTAGEM
         * ---------------------------------------------------------
         *
         * Aqui sim os filtros são aplicados.
         */
        $branchConfigurations = $this->service->getBranchConfigurations(
            $preventiveProfile,
            $filters
        );

        return view(
            'configurations.preventive-profiles.rules.index',
            [
                'preventiveProfile' => $preventiveProfile,

                'branchConfigurations' => $branchConfigurations,

                /**
                 * Dados do resumo — independentes dos filtros.
                 */
                'totalBranches' => $summary['totalBranches'],
                'configuredBranches' => $summary['configuredBranches'],
                'pendingBranches' => $summary['pendingBranches'],
                'totalSpecificRules' => $summary['totalSpecificRules'],

                /**
                 * Filtros atuais da tela.
                 */
                'filters' => $filters,
            ]
        );
    }

    /**
     * Exibe o formulário de criação de uma regra.
     */
    public function create(
        PreventiveProfile $preventiveProfile
    ): View {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        $data = $this->service->getFormData(
            $preventiveProfile
        );

        return view(
            'configurations.preventive-profiles.rules.create',
            [
                'preventiveProfile' => $preventiveProfile,
                ...$data,
            ]
        );
    }

    /**
     * Exibe o formulário de edição de uma regra.
     */
    public function edit(
        PreventiveProfile $preventiveProfile,
        PreventiveProfileRule $rule
    ): View {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $rule
        );

        $data = $this->service->getFormData(
            $preventiveProfile,
            $rule
        );

        return view(
            'configurations.preventive-profiles.rules.edit',
            [
                'preventiveProfile' => $preventiveProfile,
                'rule' => $rule,
                ...$data,
            ]
        );
    }

    /**
     * Armazena uma nova regra.
     */
    public function store(
        StorePreventiveProfileRuleRequest $request,
        PreventiveProfile $preventiveProfile
    ): RedirectResponse {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        try {
            $this->service->create(
                $preventiveProfile,
                $request->validated()
            );

            return redirect()
                ->route(
                    'configuracoes.perfis-preventivas.regras.index',
                    $preventiveProfile
                )
                ->with(
                    'success',
                    'Regra de preventiva cadastrada com sucesso.'
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Exibe os detalhes de uma regra.
     */
    public function show(
        Request $request,
        PreventiveProfile $preventiveProfile,
        PreventiveProfileRule $rule
    ): View {
        $this->authorize(
            'view',
            $preventiveProfile
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $rule
        );

        // Extrai os filtros passados na query string.
        $filters = $request->only([
            'branch_id',
            'status',
            'search',
        ]);

        // Carrega a regra com os dados necessários para a visualização.
        $rule = $this->queryService->getRuleForShow(
            $rule,
            $filters
        );


        // Dados necessários para reutilizar o modal de regra específica.
        $data = $this->service->getFormData(
            $preventiveProfile,
            $rule
        );

        return view(
            'configurations.preventive-profiles.rules.show',
            [
                'preventiveProfile' => $preventiveProfile,
                'rule' => $rule,
                'filters' => $filters,
                ...$data,
            ]
        );
    }
    /**
     * Atualiza uma regra Todos.
     */
    public function update(
        UpdatePreventiveProfileRuleRequest $request,
        PreventiveProfile $preventiveProfile,
        PreventiveProfileRule $rule
    ): RedirectResponse {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $rule
        );

        try {
            $this->service->update(
                $rule,
                $request->validated()
            );

            $rule->loadMissing([
                'preventiveProfileBranch.branch',
            ]);

            $branchName = $rule->preventiveProfileBranch?->branch?->name
                ?? 'filial';

            return redirect()
                ->route(
                    'configuracoes.perfis-preventivas.regras.index',
                    $preventiveProfile
                )
                ->with(
                    'success',
                    "Regra Todos da filial {$branchName} atualizada com sucesso."
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Cria uma regra específica para uma unidade da filial.
     */
    public function storeSpecific(
        StorePreventiveProfileSpecificRuleRequest $request,
        PreventiveProfile $preventiveProfile,
        PreventiveProfileRule $rule
    ): RedirectResponse {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $rule
        );

        try {
            $this->service->createSpecificRule(
                $preventiveProfile,
                $rule,
                $request->validated()
            );

            return redirect()
                ->route(
                    'configuracoes.perfis-preventivas.regras.edit',
                    [
                        $preventiveProfile,
                        $rule,
                    ]
                )
                ->with(
                    'success',
                    'Regra específica criada com sucesso.'
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function updateSpecific(
        UpdatePreventiveProfileSpecificRuleRequest $request,
        PreventiveProfile $preventiveProfile,
        PreventiveProfileRule $rule,
        PreventiveProfileRule $specificRule
    ): RedirectResponse {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $rule
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $specificRule
        );

        try {
            $this->service->updateSpecificRule(
                $preventiveProfile,
                $specificRule,
                $request->validated()
            );

            return redirect()
                ->route(
                    'configuracoes.perfis-preventivas.regras.edit',
                    [
                        $preventiveProfile,
                        $rule,
                    ]
                )
                ->with(
                    'success',
                    'Regra específica atualizada com sucesso.'
                );
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage())
                ->with('specific_modal_error', true);
        }
    }
    public function updateSpecificFromShow(
        UpdatePreventiveProfileSpecificRuleRequest $request,
        PreventiveProfile $preventiveProfile,
        PreventiveProfileRule $rule,
        PreventiveProfileRule $specificRule
    ): RedirectResponse {

        $this->authorize(
            'update',
            $preventiveProfile
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $rule
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $specificRule
        );

        try {
            $this->service->updateSpecificRule(
                $preventiveProfile,
                $specificRule,
                $request->validated()
            );

            return redirect()
                ->route(
                    'configuracoes.perfis-preventivas.regras.show',
                    [
                        'preventiveProfile' => $preventiveProfile,
                        'rule' => $rule,
                    ]
                )
                ->with(
                    'success',
                    'Regra específica atualizada com sucesso.'
                );
        } catch (\Throwable $e) {

            return redirect()
                ->route(
                    'configuracoes.perfis-preventivas.regras.show',
                    [
                        'preventiveProfile' => $preventiveProfile,
                        'rule' => $rule,
                    ]
                )
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
    /**
     * Exclui uma regra específica.
     */
    public function destroySpecific(
        PreventiveProfile $preventiveProfile,
        PreventiveProfileRule $rule,
        PreventiveProfileRule $specificRule
    ): RedirectResponse {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $rule
        );

        $this->service->validateRuleBelongsToProfile(
            $preventiveProfile,
            $specificRule
        );

        try {
            $this->service->destroy(
                $specificRule
            );

            return redirect()
                ->route(
                    'configuracoes.perfis-preventivas.regras.edit',
                    [
                        $preventiveProfile,
                        $rule,
                    ]
                )
                ->with(
                    'success',
                    'Regra específica excluída com sucesso.'
                );
        } catch (\Throwable $e) {
            return back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove toda a configuração de uma filial.
     *
     * A regra ALL e todas as regras específicas da filial
     * serão removidas, deixando a filial como não configurada.
     */
    public function destroyBranchConfiguration(
        PreventiveProfile $preventiveProfile,
        PreventiveProfileBranch $profileBranch
    ): RedirectResponse {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        if (
            $profileBranch->preventive_profile_id
            !== $preventiveProfile->id
        ) {
            abort(404);
        }

        try {
            $this->service->deleteBranchConfiguration(
                $profileBranch
            );

            return redirect()
                ->route(
                    'configuracoes.perfis-preventivas.regras.index',
                    $preventiveProfile
                )
                ->with(
                    'success',
                    'A configuração da filial foi removida com sucesso. A filial voltou ao estado "Não configurada".'
                );
        } catch (\Throwable $e) {
            return back()
                ->with('error', $e->getMessage());
        }
    }
}
