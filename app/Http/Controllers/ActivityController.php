<?php

namespace App\Http\Controllers;

use App\Enums\ActivityKind;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Configuration\Preventive\Activity;
use App\Models\Configuration\Preventive\ActivityCategory;
use App\Models\Configuration\Preventive\PreventiveType;
use App\Services\ActivityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class ActivityController extends Controller
{
    public function __construct(
        private ActivityService $service
    ) {}
    /**
     * Exibe a lista de atividades de um tipo de preventiva.
     */
    public function index(
        PreventiveType $preventiveType
    ): View {
        $this->authorize('viewAny', Activity::class);

        $activities = $preventiveType->activities()
            ->with('activityCategory')
            ->orderBy('name')
            ->get();

        return view(
            'configurations.preventive-types.activities.index',
            [
                'preventiveType' => $preventiveType,
                'activities' => $activities,
            ]
        );
    }

    /**
     * Exibe o formulário de criação de uma atividade.
     */
    public function create(
        PreventiveType $preventiveType
    ): View {
        $this->authorize('create', Activity::class);

        $activityTypes = ActivityKind::cases();

        $activityCategories = ActivityCategory::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view(
            'configurations.preventive-types.activities.create',
            [
                'preventiveType' => $preventiveType,
                'activityTypes' => $activityTypes,
                'activityCategories' => $activityCategories,
            ]
        );
    }

    /**
     * Armazena uma nova atividade.
     */
    public function store(
        StoreActivityRequest $request,
        PreventiveType $preventiveType
    ): RedirectResponse {
        $this->authorize('create', Activity::class);

        $validated = $request->validated();

        $preventiveType->activities()->create($validated);

        return redirect()
            ->route(
                'configuracoes.tipos-preventivas.activities.index',
                $preventiveType
            )
            ->with(
                'success',
                'Atividade cadastrada com sucesso.'
            );
    }

    /**
     * Exibe o formulário de edição de uma atividade.
     */
    public function edit(
        PreventiveType $preventiveType,
        Activity $activity
    ): View {
        $this->authorize('update', $activity);

        $activityTypes = ActivityKind::cases();

        $activityCategories = ActivityCategory::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view(
            'configurations.preventive-types.activities.edit',
            [
                'preventiveType' => $preventiveType,
                'activity' => $activity,
                'activityTypes' => $activityTypes,
                'activityCategories' => $activityCategories,
            ]
        );
    }

    /**
     * Atualiza uma atividade.
     */
    public function update(
        UpdateActivityRequest $request,
        PreventiveType $preventiveType,
        Activity $activity
    ): RedirectResponse {
        $this->authorize('update', $activity);

        $validated = $request->validated();

        $activity->update($validated);

        return redirect()
            ->route(
                'configuracoes.tipos-preventivas.activities.index',
                $preventiveType
            )
            ->with(
                'success',
                'Atividade atualizada com sucesso.'
            );
    }

    /**
     * Exibe os detalhes de uma atividade.
     */
    public function show(
        PreventiveType $preventiveType,
        Activity $activity
    ): View {
        $this->authorize('view', $activity);

        $activity->load('activityCategory');

        return view(
            'configurations.preventive-types.activities.show',
            [
                'preventiveType' => $preventiveType,
                'activity' => $activity,
            ]
        );
    }

    /**
     * Inativa uma atividade.
     */
    public function destroy(
        PreventiveType $preventiveType,
        Activity $activity
    ): RedirectResponse {
        $this->authorize('toggleActive', $activity);

        try {
            $this->service->deactivate($activity);

            return redirect()
                ->route(
                    'configuracoes.tipos-preventivas.activities.index',
                    $preventiveType
                )
                ->with(
                    'success',
                    'Atividade inativada com sucesso.'
                );
        } catch (\Throwable $e) {
            return redirect()
                ->route(
                    'configuracoes.tipos-preventivas.activities.index',
                    $preventiveType
                )
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Ativa uma atividade.
     */
    public function activate(
        PreventiveType $preventiveType,
        Activity $activity
    ): RedirectResponse {
        $this->authorize('toggleActive', $activity);

        $activity->update([
            'active' => true,
        ]);

        return redirect()
            ->route(
                'configuracoes.tipos-preventivas.activities.index',
                $preventiveType
            )
            ->with(
                'success',
                'Atividade ativada com sucesso.'
            );
    }
}
