<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use App\Models\Equipment\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    /**
     * Lista os fabricantes.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Manufacturer::class);

        $manufacturers = Manufacturer::orderBy('name')
            ->paginate(15);

        return view(
            'manufacturers.index',
            compact('manufacturers')
        );
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $this->authorize('create', Manufacturer::class);

        return view('manufacturers.create');
    }

    /**
     * Cadastra uma nova categoria.
     */
    public function store(
        StoreManufacturerRequest $request
    ): RedirectResponse {

        $this->authorize('create', Manufacturer::class);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        Manufacturer::create($validated);

        return redirect()
            ->route('fabricantes.index')
            ->with(
                'success',
                'Fabricante cadastrado com sucesso.'
            );
    }

    /**
     * Formulário de edição.
     */
    public function edit(
        Manufacturer $manufacturer
    ): View {

        $this->authorize('update', $manufacturer);

        return view(
            'manufacturers.edit',
            compact('manufacturer')
        );
    }

    /**
     * Atualiza um fabricante.
     */
    public function update(
        UpdateManufacturerRequest $request,
        Manufacturer $manufacturer
    ): RedirectResponse {

        $this->authorize('update', $manufacturer);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        $manufacturer->update($validated);

        return redirect()
            ->route('fabricantes.index')
            ->with(
                'success',
                'Fabricante atualizado com sucesso.'
            );
    }

    /**
     * Ativa/Inativa um fabricante.
     */
    public function toggleActive(
        Manufacturer $manufacturer
    ): RedirectResponse {

        $this->authorize(
            'toggleActive',
            $manufacturer
        );

        $manufacturer->update([
            'active' => ! $manufacturer->active,
        ]);

        return redirect()
            ->route('fabricantes.index')
            ->with(
                'success',
                $manufacturer->active
                    ? 'Fabricante ativado com sucesso.'
                    : 'Fabricante inativado com sucesso.'
            );
    }
}
