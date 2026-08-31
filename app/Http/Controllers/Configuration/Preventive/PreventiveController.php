<?php

declare(strict_types=1);

namespace App\Http\Controllers\Configuration\Preventive;

use App\Http\Controllers\Controller;

use App\Http\Requests\Preventive\StorePreventiveRequest;
use App\Http\Requests\Preventive\StorePreventiveContinuationRequest;

use App\Services\Preventive\Continuation\CreatePreventiveContinuationService;
use App\Models\Organization\Branch;
use App\Models\Preventive\Preventive;
use App\Models\Configuration\Preventive\PreventiveProfile;
use App\Models\Configuration\Preventive\PreventiveType;
use App\Models\Access\User;
use App\Services\Preventive\Creation\CreatePreventiveService;
use App\Services\Preventive\ResolvePreventiveConfigurationService;
use App\Services\Preventive\Validation\GetPreventiveValidationService;
use App\Services\Preventive\Validation\ApprovePreventiveService;
use App\Services\Preventive\Validation\RejectPreventiveService;
use App\Services\Preventive\Query\GetPreventiveShowDetailsService;
use App\Services\Preventive\Continuation\GetPreventiveContinuationService;
use App\Services\Preventive\Continuation\GetPreventiveContinuationUnitsService;
use App\Services\Preventive\Continuation\GetPreventiveContinuationActivitiesService;
use App\Services\Preventive\Query\GetPreventivesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class PreventiveController extends Controller
{
    /**
     * Lista as preventivas cadastradas.
     */
    public function index(
        Request $request,
        GetPreventivesService $service
    ): View {
        $filters = $request->only([
            'search',
            'status',
            'branch_id',
            'preventive_type_id',
        ]);

        $data = $service->execute($filters);

        return view(
            'configurations.preventives.index',
            $data
        );
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create(): View
    {
        $this->authorize('create', Preventive::class);

        $branches = Branch::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        $users = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'configurations.preventives.create',
            [
                'branches' => $branches,
                'users' => $users,
            ]
        );
    }

    /**
     * Retorna os tipos de preventiva disponíveis para uma filial.
     *
     * Um tipo somente aparece quando existe um perfil ativo
     * desse tipo associado à filial.
     */
    public function types(Branch $branch): JsonResponse
    {
        $this->authorize('create', Preventive::class);

        $types = PreventiveType::query()
            ->where('active', true)
            ->whereHas('profiles', function ($query) use ($branch) {
                $query
                    ->where('active', true)
                    ->whereHas('branches', function ($query) use ($branch) {
                        $query->where('branch_id', $branch->id);
                    });
            })
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return response()->json($types);
    }

    /**
     * Retorna os perfis disponíveis para a combinação
     * filial + tipo de preventiva.
     */
    public function profiles(
        Branch $branch,
        PreventiveType $preventiveType
    ): JsonResponse {
        $this->authorize('create', Preventive::class);

        $profiles = PreventiveProfile::query()
            ->where('preventive_type_id', $preventiveType->id)
            ->where('active', true)
            ->whereHas('branches', function ($query) use ($branch) {
                $query->where('branch_id', $branch->id);
            })
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'description',
            ]);

        return response()->json($profiles);
    }

    /**
     * Retorna a configuração efetiva do perfil para a filial.
     *
     * A resolução das unidades e regras é responsabilidade do
     * ResolvePreventiveConfigurationService.
     */
    public function configuration(
        Branch $branch,
        PreventiveProfile $preventiveProfile,
        ResolvePreventiveConfigurationService $service
    ): JsonResponse {
        $this->authorize('create', Preventive::class);

        return response()->json(
            $service->execute(
                $branch,
                $preventiveProfile
            )
        );
    }
    /**
     * Cria uma nova preventiva.
     */
    public function store(
        StorePreventiveRequest $request,
        CreatePreventiveService $service
    ): RedirectResponse {
        $this->authorize('create', Preventive::class);

        $preventive = $service->execute(
            $request->validated(),
            $request->user()->id
        );

        return redirect()
            ->route('preventivas.show', $preventive)
            ->with('success', 'Preventiva criada com sucesso.');
    }

    /**
     * Exibe os dados da preventiva para validação do gestor.
     */
    public function validate(
        Preventive $preventive,
        GetPreventiveValidationService $service
    ): View {
        $this->authorize('validate', $preventive);

        $data = $service->execute($preventive);

        return view(
            'configurations.preventives.validation',
            $data
        );
    }

    public function approve(
        Preventive $preventive,
        ApprovePreventiveService $service
    ): RedirectResponse {
        $this->authorize('validate', $preventive);

        $service->execute(
            $preventive,
            request()->user()
        );

        return redirect()
            ->route('preventivas.index')
            ->with(
                'success',
                'Preventiva aprovada com sucesso.'
            );
    }

    public function reject(
        Request $request,
        Preventive $preventive,
        RejectPreventiveService $service
    ): RedirectResponse {
        $this->authorize('validate', $preventive);

        $validated = $request->validate([
            'review_observation' => [
                'required',
                'string',
                'min:5',
                'max:5000',
            ],
        ]);

        $service->execute(
            preventive: $preventive,
            user: $request->user(),
            observation: $validated['review_observation'],
        );

        return redirect()
            ->route(
                'preventivas.continuation',
                $preventive
            )
            ->with(
                'success',
                'Preventiva reprovada. Selecione as unidades e atividades que deverão ser refeitas.'
            );
    }

    public function continuationUnits(
        Preventive $preventive,
        Request $request,
        GetPreventiveContinuationUnitsService $service
    ): JsonResponse {
        $this->authorize('continue', $preventive);

        return response()->json(
            $service->execute(
                preventive: $preventive,
                search: $request->string('search')->toString(),
            )
        );
    }

    public function continuationActivities(
        Preventive $preventive,
        int $operationalUnitId,
        GetPreventiveContinuationActivitiesService $service
    ): JsonResponse {
        $this->authorize(
            'continue',
            $preventive
        );

        return response()->json(
            $service->execute(
                preventive: $preventive,
                operationalUnitId: $operationalUnitId,
            )
        );
    }

    /**
     * Exibe o formulário de continuidade da preventiva
     * após a reprovação de um Cycle.
     */
    public function continuation(
        Preventive $preventive,
        GetPreventiveContinuationService $service
    ): View {
        $this->authorize('continue', $preventive);

        $data = $service->execute($preventive);

        return view(
            'configurations.preventives.continuation',
            $data
        );
    }

    public function storeContinuation(
        StorePreventiveContinuationRequest $request,
        Preventive $preventive,
        CreatePreventiveContinuationService $service
    ): RedirectResponse {
        $this->authorize('continue', $preventive);

        $service->execute(
            preventive: $preventive,
            units: $request->validated()['units'],
        );

        return redirect()
            ->route('preventivas.show', $preventive)
            ->with(
                'success',
                'Continuidade da preventiva criada com sucesso.'
            );
    }

    /**
     * Exibe os detalhes completos da preventiva.
     */
    public function show(
        Preventive $preventive,
        GetPreventiveShowDetailsService $service
    ): View {
        $this->authorize('view', $preventive);

        $data = $service->execute($preventive);

        return view(
            'configurations.preventives.show',
            $data
        );
    }
}
