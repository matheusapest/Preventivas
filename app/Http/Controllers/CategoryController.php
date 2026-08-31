<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Configuration\Operational\Category;
use App\Models\Configuration\Operational\UnitType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Lista as categorias.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::query()
            ->with('unitTypes')
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $query->where(
                        'name',
                        'like',
                        '%' . $request->string('search') . '%'
                    );
                }
            )
            ->when(
                $request->filled('unit_type_id'),
                function ($query) use ($request) {
                    $query->whereHas('unitTypes', function ($query) use ($request) {
                        $query->where(
                            'unit_types.id',
                            $request->integer('unit_type_id')
                        );
                    });
                }
            )
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $unitTypes = UnitType::query()
            ->active()
            ->orderBy('name')
            ->get();

        return view('categories.index', compact(
            'categories',
            'unitTypes'
        ));
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $this->authorize('create', Category::class);

        $unitTypes = UnitType::query()
            ->active()
            ->orderBy('name')
            ->get();

        return view(
            'categories.create',
            compact('unitTypes')
        );
    }

    /**
     * Cadastra uma nova categoria.
     */
    public function store(
        StoreCategoryRequest $request
    ): RedirectResponse {
        $this->authorize('create', Category::class);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        $unitTypeIds = $validated['unit_type_ids'] ?? [];

        unset($validated['unit_type_ids']);

        $category = Category::create($validated);

        $category->unitTypes()->sync($unitTypeIds);

        return redirect()
            ->route('categorias.index')
            ->with(
                'success',
                'Categoria cadastrada com sucesso.'
            );
    }

    /**
     * Formulário de edição.
     */
    public function edit(
        Category $category
    ): View {
        $this->authorize('update', $category);

        $category->load('unitTypes');

        $unitTypes = UnitType::query()
            ->where(function ($query) use ($category) {
                $query
                    ->where('active', true)
                    ->orWhereHas('categories', function ($query) use ($category) {
                        $query->where(
                            'categories.id',
                            $category->id
                        );
                    });
            })
            ->orderBy('name')
            ->get();

        $selectedUnitTypeIds = $category
            ->unitTypes
            ->pluck('id')
            ->values()
            ->all();

        return view(
            'categories.edit',
            compact(
                'category',
                'unitTypes',
                'selectedUnitTypeIds'
            )
        );
    }

    /**
     * Atualiza uma categoria.
     */
    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $this->authorize('update', $category);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        $unitTypeIds = $validated['unit_type_ids'] ?? [];

        unset($validated['unit_type_ids']);

        $category->update($validated);

        $category->unitTypes()->sync($unitTypeIds);

        return redirect()
            ->route('categorias.index')
            ->with(
                'success',
                'Categoria atualizada com sucesso.'
            );
    }

    /**
     * Ativa/Inativa uma categoria.
     */
    public function toggleActive(
        Category $category
    ): RedirectResponse {
        $this->authorize(
            'toggleActive',
            $category
        );

        $category->update([
            'active' => ! $category->active,
        ]);

        return redirect()
            ->route('categorias.index')
            ->with(
                'success',
                $category->active
                    ? 'Categoria ativada com sucesso.'
                    : 'Categoria inativada com sucesso.'
            );
    }
}
