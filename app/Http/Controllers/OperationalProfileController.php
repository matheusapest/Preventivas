<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationalProfileRequest;
use App\Http\Requests\UpdateOperationalProfileRequest;
use App\Models\Category;
use App\Models\Configuration\Operational\OperationalProfile;
use App\Models\Configuration\Operational\UnitType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OperationalProfileController extends Controller
{
    /**
     * Exibe a lista de perfis operacionais.
     */
    public function index(): View
    {
        $this->authorize('viewAny', OperationalProfile::class);

        $operationalProfiles = OperationalProfile::query()
            ->with([
                'unitType',
                'categories.category',
            ])
            ->orderBy('unit_type_id')
            ->orderBy('name')
            ->get();

        return view(
            'configurations.operational-profiles.index',
            [
                'operationalProfiles' => $operationalProfiles,
            ]
        );
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create(): View
    {
        $this->authorize('create', OperationalProfile::class);

        $unitTypes = UnitType::query()
            ->active()
            ->orderBy('name')
            ->get();

        /**
         * Carrega todas as categorias ativas.
         *
         * A filtragem das categorias compatíveis com o tipo
         * de unidade será feita pelo JavaScript utilizando
         * os unit_type_ids enviados em categoriesData.
         */
        $categories = Category::query()
            ->where('active', true)
            ->with('unitTypes:id')
            ->orderBy('name')
            ->get();

        /**
         * Estrutura consumida pelo operationalProfile.js.
         *
         * Uma categoria pode pertencer a vários tipos de unidade.
         */
        $categoriesData = $categories
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'unit_type_ids' => $category->unitTypes
                    ->pluck('id')
                    ->values()
                    ->all(),
            ])
            ->values();

        return view(
            'configurations.operational-profiles.create',
            [
                'unitTypes' => $unitTypes,
                'categories' => $categories,
                'categoriesData' => $categoriesData,
            ]
        );
    }

    /**
     * Armazena um novo perfil operacional.
     */
    public function store(
        StoreOperationalProfileRequest $request
    ): RedirectResponse {
        $this->authorize('create', OperationalProfile::class);

        $validated = $request->validated();

        $categories = $validated['categories'] ?? [];

        unset($validated['categories']);

        DB::transaction(function () use (
            $validated,
            $categories
        ) {
            $operationalProfile = OperationalProfile::create(
                $validated
            );

            foreach ($categories as $category) {
                if ((int) $category['quantity'] <= 0) {
                    continue;
                }

                $operationalProfile->categories()->create([
                    'category_id' => $category['category_id'],
                    'quantity' => $category['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('configuracoes.perfis-operacionais.index')
            ->with(
                'success',
                'Perfil operacional cadastrado com sucesso.'
            );
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(
        OperationalProfile $operationalProfile
    ): View {
        $this->authorize('update', $operationalProfile);

        $operationalProfile->load([
            'unitType',
            'categories.category',
        ]);

        /**
         * Carrega todas as categorias ativas.
         *
         * A compatibilidade com o tipo de unidade do perfil
         * será determinada através do relacionamento unitTypes.
         */
        $categories = Category::query()
            ->where('active', true)
            ->with('unitTypes:id')
            ->orderBy('name')
            ->get();

        /**
         * Estrutura consumida pelo operationalProfile.js.
         *
         * Cada categoria pode possuir vários tipos de unidade.
         */
        $categoriesData = $categories
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'unit_type_ids' => $category->unitTypes
                    ->pluck('id')
                    ->values()
                    ->all(),
            ])
            ->values();

        /**
         * Categorias já vinculadas ao perfil.
         */
        $existingCategories = $operationalProfile->categories
            ->map(fn ($item) => [
                'category_id' => $item->category_id,
                'quantity' => $item->quantity,
            ])
            ->values();

        return view(
            'configurations.operational-profiles.edit',
            [
                'operationalProfile' => $operationalProfile,
                'categories' => $categories,
                'categoriesData' => $categoriesData,
                'existingCategories' => $existingCategories,
            ]
        );
    }

    /**
     * Atualiza um perfil operacional.
     */
    public function update(
        UpdateOperationalProfileRequest $request,
        OperationalProfile $operationalProfile
    ): RedirectResponse {
        $this->authorize('update', $operationalProfile);

        $validated = $request->validated();

        $categories = $validated['categories'] ?? [];

        unset($validated['categories']);

        DB::transaction(function () use (
            $operationalProfile,
            $validated,
            $categories
        ) {
            $operationalProfile->update($validated);

            /**
             * Sincroniza a composição do perfil.
             *
             * A composição anterior é removida e reconstruída
             * com os dados enviados pelo formulário.
             *
             * Categorias com quantidade 0 não são gravadas.
             */
            $operationalProfile->categories()->delete();

            foreach ($categories as $category) {
                if ((int) $category['quantity'] <= 0) {
                    continue;
                }

                $operationalProfile->categories()->create([
                    'category_id' => $category['category_id'],
                    'quantity' => $category['quantity'],
                ]);
            }
        });

        return redirect()
            ->route('configuracoes.perfis-operacionais.index')
            ->with(
                'success',
                'Perfil operacional atualizado com sucesso.'
            );
    }

    /**
     * Inativa um perfil operacional.
     */
    public function destroy(
        OperationalProfile $operationalProfile
    ): RedirectResponse {
        $this->authorize('toggleActive', $operationalProfile);

        $operationalProfile->update([
            'active' => false,
        ]);

        return redirect()
            ->route('configuracoes.perfis-operacionais.index')
            ->with(
                'success',
                'Perfil operacional inativado com sucesso.'
            );
    }

    /**
     * Ativa um perfil operacional.
     */
    public function activate(
        OperationalProfile $operationalProfile
    ): RedirectResponse {
        $this->authorize('toggleActive', $operationalProfile);

        $operationalProfile->update([
            'active' => true,
        ]);

        return redirect()
            ->route('configuracoes.perfis-operacionais.index')
            ->with(
                'success',
                'Perfil operacional ativado com sucesso.'
            );
    }
}
