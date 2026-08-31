<?php

declare(strict_types=1);

namespace App\Http\Controllers\Configuration\Preventive;

use App\Http\Controllers\Controller;

use App\Http\Requests\Configuration\Preventive\StorePreventiveProfileRequest;
use App\Http\Requests\Configuration\Preventive\UpdatePreventiveProfileRequest;
use App\Models\PreventiveProfile;
use App\Models\PreventiveType;
use App\Services\PreventiveProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PreventiveProfileController extends Controller
{
    public function __construct(
        private readonly PreventiveProfileService $service
    ) {}

    /**
     * Exibe a lista de perfis de preventiva.
     */
    public function index(): View
    {
        $this->authorize(
            'viewAny',
            PreventiveProfile::class
        );

        $profiles = PreventiveProfile::query()
            ->with([
                'preventiveType',
                'branches.branch',
            ])
            ->orderBy('name')
            ->get();

        return view(
            'configurations.preventive-profiles.index',
            [
                'profiles' => $profiles,
            ]
        );
    }

    /**
     * Exibe o formulário de criação.
     *
     * As filiais não são carregadas inicialmente.
     *
     * Elas serão carregadas dinamicamente após o gestor
     * selecionar o tipo de preventiva.
     */
    public function create(): View
    {
        $this->authorize(
            'create',
            PreventiveProfile::class
        );

        $preventiveTypes = PreventiveType::query()
            ->where('active', true)
            ->whereHas('activities', function ($query) {
                $query->where('active', true);
            })
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        /*
         * Inicialmente não existem filiais para exibir.
         *
         * A lista será carregada pelo endpoint
         * eligibleBranches() conforme o tipo selecionado.
         */
        $branches = collect();

        return view(
            'configurations.preventive-profiles.create',
            [
                'preventiveTypes' => $preventiveTypes,
                'branches' => $branches,
            ]
        );
    }

    /**
     * Retorna as filiais elegíveis para um tipo de preventiva.
     *
     * A filial precisa:
     * - estar ativa;
     * - possuir pelo menos uma unidade operacional ativa;
     * - possuir uma unidade operacional compatível com o tipo
     *   de unidade da preventiva.
     */
    public function eligibleBranches(
        PreventiveType $preventiveType
    ): JsonResponse {
        $this->authorize(
            'create',
            PreventiveProfile::class
        );

        $branches = $this->service->getEligibleBranches(
            $preventiveType
        );

        return response()->json($branches);
    }

    /**
     * Armazena um novo perfil de preventiva.
     */
    public function store(
        StorePreventiveProfileRequest $request
    ): RedirectResponse {
        $this->authorize(
            'create',
            PreventiveProfile::class
        );

        $this->service->create(
            $request->validated()
        );

        return redirect()
            ->route(
                'configuracoes.perfis-preventivas.index'
            )
            ->with(
                'success',
                'Perfil de preventiva cadastrado com sucesso.'
            );
    }

    /**
     * Exibe um perfil de preventiva.
     */
    public function show(
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

        return view(
            'configurations.preventive-profiles.show',
            [
                'preventiveProfile' => $preventiveProfile,
            ]
        );
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(
        PreventiveProfile $preventiveProfile
    ): View {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        $preventiveProfile->load([
            'preventiveType',
            'branches.branch',
        ]);

        $preventiveTypes = PreventiveType::query()
            ->where('active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        /*
         * No edit precisamos carregar as filiais atualmente
         * vinculadas ao perfil.
         *
         * O comportamento dinâmico para troca do tipo de preventiva
         * será tratado pelo mesmo endpoint de filiais elegíveis.
         */
        $branches = $preventiveProfile->branches()
            ->with('branch')
            ->get();

        return view(
            'configurations.preventive-profiles.edit',
            [
                'preventiveProfile' => $preventiveProfile,
                'preventiveTypes' => $preventiveTypes,
                'branches' => $branches,
            ]
        );
    }

    /**
     * Atualiza um perfil de preventiva.
     */
    public function update(
        UpdatePreventiveProfileRequest $request,
        PreventiveProfile $preventiveProfile
    ): RedirectResponse {
        $this->authorize(
            'update',
            $preventiveProfile
        );

        $this->service->update(
            $preventiveProfile,
            $request->validated()
        );

        return redirect()
            ->route(
                'configuracoes.perfis-preventivas.index'
            )
            ->with(
                'success',
                'Perfil de preventiva atualizado com sucesso.'
            );
    }

    /**
     * Alterna o status ativo/inativo do perfil.
     */
    public function toggleActive(
        PreventiveProfile $preventiveProfile
    ): RedirectResponse {
        $this->authorize(
            'toggleActive',
            $preventiveProfile
        );

        if ($preventiveProfile->active) {
            $this->service->deactivate(
                $preventiveProfile
            );

            $message = 'Perfil de preventiva inativado com sucesso.';
        } else {
            $this->service->activate(
                $preventiveProfile
            );

            $message = 'Perfil de preventiva ativado com sucesso.';
        }

        return redirect()
            ->route(
                'configuracoes.perfis-preventivas.index'
            )
            ->with(
                'success',
                $message
            );
    }
}
