<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitTypeRequest;
use App\Http\Requests\UpdateUnitTypeRequest;
use App\Models\Branch;
use App\Models\Configuration\Operational\UnitType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UnitTypeController extends Controller
{
    /**
     * Exibe a lista de tipos de unidade.
     */
    public function index(): View
    {
        $this->authorize('viewAny', UnitType::class);

        $unitTypes = UnitType::query()
            ->with('branches')
            ->orderBy('name')
            ->paginate(15);

        return view('configurations.unit-types.index', [
            'unitTypes' => $unitTypes,
        ]);
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create(): View
    {
        $this->authorize('create', UnitType::class);

        $branches = Branch::query()
            ->active()
            ->orderBy('name')
            ->get();

        return view('configurations.unit-types.create', [
            'branches' => $branches,
        ]);
    }

    /**
     * Armazena um novo tipo de unidade.
     */
    public function store(
        StoreUnitTypeRequest $request
    ): RedirectResponse {
        $this->authorize('create', UnitType::class);

        $validated = $request->validated();

        $branchIds = $validated['branches'] ?? [];

        unset($validated['branches']);

        DB::transaction(function () use (
            $validated,
            $branchIds
        ) {
            $unitType = UnitType::create($validated);

            $unitType->branches()->sync($branchIds);
        });

        return redirect()
            ->route('configuracoes.tipos-unidade.index')
            ->with(
                'success',
                'Tipo de unidade cadastrado com sucesso.'
            );
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(UnitType $unitType): View
    {
        $this->authorize('update', $unitType);

        $unitType->load('branches');

        $branches = Branch::query()
            ->active()
            ->orderBy('name')
            ->get();

        return view('configurations.unit-types.edit', [
            'unitType' => $unitType,
            'branches' => $branches,
        ]);
    }

    /**
     * Atualiza um tipo de unidade.
     */
    public function update(
        UpdateUnitTypeRequest $request,
        UnitType $unitType
    ): RedirectResponse {
        $this->authorize('update', $unitType);

        $validated = $request->validated();

        $branchIds = $validated['branches'] ?? [];

        unset($validated['branches']);

        DB::transaction(function () use (
            $unitType,
            $validated,
            $branchIds
        ) {
            $unitType->update($validated);

            $unitType->branches()->sync($branchIds);
        });

        return redirect()
            ->route('configuracoes.tipos-unidade.index')
            ->with(
                'success',
                'Tipo de unidade atualizado com sucesso.'
            );
    }

    /**
     * Inativa um tipo de unidade.
     */
    public function destroy(UnitType $unitType): RedirectResponse
    {
        $this->authorize('update', $unitType);

        $unitType->update([
            'active' => false,
        ]);

        return redirect()
            ->route('configuracoes.tipos-unidade.index')
            ->with(
                'success',
                'Tipo de unidade inativado com sucesso.'
            );
    }

    /**
     * Ativa um tipo de unidade.
     */
    public function activate(UnitType $unitType): RedirectResponse
    {
        $this->authorize('update', $unitType);

        $unitType->update([
            'active' => true,
        ]);

        return redirect()
            ->route('configuracoes.tipos-unidade.index')
            ->with(
                'success',
                'Tipo de unidade ativado com sucesso.'
            );
    }
}
