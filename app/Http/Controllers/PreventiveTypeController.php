<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePreventiveTypeRequest;
use App\Http\Requests\UpdatePreventiveTypeRequest;
use App\Models\PreventiveType;
use App\Models\UnitType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PreventiveTypeController extends Controller
{
    /**
     * Exibe a lista de tipos de preventiva.
     */
    public function index(): View
    {
        $this->authorize('viewAny', PreventiveType::class);

        $preventiveTypes = PreventiveType::query()
            ->with('unitType')
            ->orderBy('name')
            ->get();

        return view(
            'configurations.preventive-types.index',
            [
                'preventiveTypes' => $preventiveTypes,
            ]
        );
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create(): View
    {
        $this->authorize('create', PreventiveType::class);

        $unitTypes = UnitType::query()
            ->active()
            ->orderBy('name')
            ->get();

        return view(
            'configurations.preventive-types.create',
            [
                'unitTypes' => $unitTypes,
            ]
        );
    }

    /**
     * Armazena um novo tipo de preventiva.
     */
    public function store(
        StorePreventiveTypeRequest $request
    ): RedirectResponse {
        $this->authorize('create', PreventiveType::class);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        PreventiveType::create($validated);

        return redirect()
            ->route('configuracoes.tipos-preventivas.index')
            ->with(
                'success',
                'Tipo de preventiva cadastrado com sucesso.'
            );
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(
        PreventiveType $preventiveType
    ): View {
        $this->authorize('update', $preventiveType);

        $preventiveType->load('unitType');

        return view(
            'configurations.preventive-types.edit',
            [
                'preventiveType' => $preventiveType,
            ]
        );
    }

    /**
     * Atualiza um tipo de preventiva.
     */
    public function update(
        UpdatePreventiveTypeRequest $request,
        PreventiveType $preventiveType
    ): RedirectResponse {
        $this->authorize('update', $preventiveType);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        $preventiveType->update($validated);

        return redirect()
            ->route('configuracoes.tipos-preventivas.index')
            ->with(
                'success',
                'Tipo de preventiva atualizado com sucesso.'
            );
    }

    /**
     * Inativa um tipo de preventiva.
     */
    public function destroy(
        PreventiveType $preventiveType
    ): RedirectResponse {
        $this->authorize('toggleActive', $preventiveType);

        $preventiveType->update([
            'active' => false,
        ]);

        return redirect()
            ->route('configuracoes.tipos-preventivas.index')
            ->with(
                'success',
                'Tipo de preventiva inativado com sucesso.'
            );
    }

    /**
     * Ativa um tipo de preventiva.
     */
    public function activate(
        PreventiveType $preventiveType
    ): RedirectResponse {
        $this->authorize('toggleActive', $preventiveType);

        $preventiveType->update([
            'active' => true,
        ]);

        return redirect()
            ->route('configuracoes.tipos-preventivas.index')
            ->with(
                'success',
                'Tipo de preventiva ativado com sucesso.'
            );
    }
}
