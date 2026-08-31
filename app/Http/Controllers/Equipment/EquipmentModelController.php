<?php

namespace App\Http\Controllers\Equipment;

use App\Http\Controllers\Controller;

use App\Http\Requests\Equipment\StoreEquipmentModelRequest;
use App\Http\Requests\Equipment\UpdateEquipmentModelRequest;
use App\Models\Configuration\Operational\Category;
use App\Models\Equipment\EquipmentModel;
use App\Models\Equipment\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EquipmentModelController extends Controller
{
    /**
     * Lista os modelos.
     */
    public function index(): View
    {
        $this->authorize('viewAny', EquipmentModel::class);

        $equipmentModels = EquipmentModel::with([
            'manufacturer',
            'category',
        ])
            ->orderBy('name')
            ->paginate(15);

        return view(
            'equipment-models.index',
            compact('equipmentModels')
        );
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $this->authorize('create', EquipmentModel::class);

        $manufacturers = Manufacturer::active()
            ->orderBy('name')
            ->get();

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view(
            'equipment-models.create',
            compact(
                'manufacturers',
                'categories'
            )
        );
    }

    /**
     * Cadastra um novo modelo.
     */
    public function store(
        StoreEquipmentModelRequest $request
    ): RedirectResponse {

        $this->authorize('create', EquipmentModel::class);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        EquipmentModel::create($validated);

        return redirect()
            ->route('modelos-equipamentos.index')
            ->with(
                'success',
                'Modelo cadastrado com sucesso.'
            );
    }

    /**
     * Formulário de edição.
     */
    public function edit(
        EquipmentModel $equipmentModel
    ): View {

        $this->authorize('update', $equipmentModel);

        $manufacturers = Manufacturer::active()
            ->orderBy('name')
            ->get();

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view(
            'equipment-models.edit',
            compact(
                'equipmentModel',
                'manufacturers',
                'categories'
            )
        );
    }

    /**
     * Atualiza um modelo.
     */
    public function update(
        UpdateEquipmentModelRequest $request,
        EquipmentModel $equipmentModel
    ): RedirectResponse {

        $this->authorize('update', $equipmentModel);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        $equipmentModel->update($validated);

        return redirect()
            ->route('modelos-equipamentos.index')
            ->with(
                'success',
                'Modelo atualizado com sucesso.'
            );
    }

    /**
     * Ativa/Inativa um modelo.
     */
    public function toggleActive(
        EquipmentModel $equipmentModel
    ): RedirectResponse {

        $this->authorize(
            'toggleActive',
            $equipmentModel
        );

        $equipmentModel->update([
            'active' => ! $equipmentModel->active,
        ]);

        return redirect()
            ->route('modelos-equipamentos.index')
            ->with(
                'success',
                $equipmentModel->active
                    ? 'Modelo ativado com sucesso.'
                    : 'Modelo inativado com sucesso.'
            );
    }
}
