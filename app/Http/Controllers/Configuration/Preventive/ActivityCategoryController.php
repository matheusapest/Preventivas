<?php

namespace App\Http\Controllers\Configuration\Preventive;

use App\Http\Controllers\Controller;

use App\Http\Requests\Configuration\Preventive\StoreActivityCategoryRequest;
use App\Http\Requests\Configuration\Preventive\UpdateActivityCategoryRequest;
use App\Models\Configuration\Preventive\ActivityCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ActivityCategoryController extends Controller
{
    /**
     * Exibe a lista de categorias de atividades.
     */
    public function index(): View
    {
        $this->authorize('viewAny', ActivityCategory::class);

        $activityCategories = ActivityCategory::query()
            ->orderBy('name')
            ->get();

        return view(
            'configurations.preventive-types.activity-categories.index',
            [
                'activityCategories' => $activityCategories,
            ]
        );
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create(): View
    {
        $this->authorize('create', ActivityCategory::class);

        return view(
            'configurations.preventive-types.activity-categories.create'
        );
    }

    /**
     * Armazena uma nova categoria.
     */
    public function store(
        StoreActivityCategoryRequest $request
    ): RedirectResponse {
        $this->authorize('create', ActivityCategory::class);

        $validated = $request->validated();

        ActivityCategory::create($validated);

        return redirect()
            ->route(
                'configuracoes.activity-categories.index'
            )
            ->with(
                'success',
                'Categoria de atividade cadastrada com sucesso.'
            );
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(
        ActivityCategory $activityCategory
    ): View {
        $this->authorize('update', $activityCategory);

        return view(
            'configurations.preventive-types.activity-categories.edit',
            [
                'activityCategory' => $activityCategory,
            ]
        );
    }

    /**
     * Atualiza uma categoria.
     */
    public function update(
        UpdateActivityCategoryRequest $request,
        ActivityCategory $activityCategory
    ): RedirectResponse {
        $this->authorize('update', $activityCategory);

        $validated = $request->validated();

        $activityCategory->update($validated);

        return redirect()
            ->route(
                'configuracoes.activity-categories.index'
            )
            ->with(
                'success',
                'Categoria de atividade atualizada com sucesso.'
            );
    }

    /**
     * Inativa uma categoria.
     */
    public function destroy(
        ActivityCategory $activityCategory
    ): RedirectResponse {
        $this->authorize('toggleActive', $activityCategory);

        $activityCategory->update([
            'active' => false,
        ]);

        return redirect()
            ->route(
                'configuracoes.activity-categories.index'
            )
            ->with(
                'success',
                'Categoria de atividade inativada com sucesso.'
            );
    }

    /**
     * Ativa uma categoria.
     */
    public function activate(
        ActivityCategory $activityCategory
    ): RedirectResponse {
        $this->authorize('toggleActive', $activityCategory);

        $activityCategory->update([
            'active' => true,
        ]);

        return redirect()
            ->route(
                'configuracoes.activity-categories.index'
            )
            ->with(
                'success',
                'Categoria de atividade ativada com sucesso.'
            );
    }
}
