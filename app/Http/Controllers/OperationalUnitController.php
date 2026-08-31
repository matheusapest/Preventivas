<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationalUnitRequest;
use App\Http\Requests\UpdateOperationalUnitRequest;
use App\Models\Branch;
use App\Models\Configuration\Operational\OperationalProfile;
use App\Models\OperationalUnit;
use App\Models\UnitType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\StoreMultipleOperationalUnitRequest;
use App\Services\OperationalUnitService;

class OperationalUnitController extends Controller
{
    /**
     * Exibe a lista de unidades operacionais.
     */
    /**
     * Exibe a lista de unidades operacionais.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', OperationalUnit::class);

        $branches = Branch::query()
            ->active()
            ->orderBy('name')
            ->get();

        $unitTypes = UnitType::query()
            ->active()
            ->orderBy('name')
            ->get();

        $operationalProfiles = OperationalProfile::query()
            ->active()
            ->with('unitType')
            ->orderBy('name')
            ->get();

        $query = OperationalUnit::query()
            ->with([
                'branch',
                'unitType',
                'operationalProfile',
            ]);

        /*
     * Busca pelo identificador.
     */
        $query->when(
            $request->filled('search'),
            function ($query) use ($request) {
                $query->where(
                    'identifier',
                    'like',
                    '%' . $request->string('search') . '%'
                );
            }
        );

        /*
     * Filtro por filial.
     */
        $query->when(
            $request->filled('branch_id'),
            function ($query) use ($request) {
                $query->where(
                    'branch_id',
                    $request->integer('branch_id')
                );
            }
        );

        /*
     * Filtro por tipo de unidade.
     */
        $query->when(
            $request->filled('unit_type_id'),
            function ($query) use ($request) {
                $query->where(
                    'unit_type_id',
                    $request->integer('unit_type_id')
                );
            }
        );

        /*
     * Filtro por perfil operacional.
     */
        $query->when(
            $request->filled('operational_profile_id'),
            function ($query) use ($request) {
                $query->where(
                    'operational_profile_id',
                    $request->integer('operational_profile_id')
                );
            }
        );

        /*
     * Filtro por status.
     */
        $query->when(
            $request->filled('status'),
            function ($query) use ($request) {
                $query->where(
                    'active',
                    $request->input('status') === 'active'
                );
            }
        );

        $operationalUnits = $query
            ->orderBy('branch_id')
            ->orderBy('unit_type_id')
            ->orderBy('identifier')
            ->paginate(15)
            ->withQueryString();

        return view(
            'configurations.operational-units.index',
            [
                'operationalUnits' => $operationalUnits,
                'branches' => $branches,
                'unitTypes' => $unitTypes,
                'operationalProfiles' => $operationalProfiles,
            ]
        );
    }

    /**
     * Exibe o formulário de criação.
     *
     * O cadastro pode ser realizado:
     *
     * - individualmente;
     * - em lote.
     *
     * Ambos os formulários são renderizados na mesma página.
     * O JavaScript apenas alterna qual formulário fica visível.
     */
    public function create(): View
    {
        $this->authorize('create', OperationalUnit::class);

        /*
     * --------------------------------------------------------------------------
     * Filiais
     * --------------------------------------------------------------------------
     */

        $branches = Branch::query()
            ->active()
            ->orderBy('name')
            ->get();

        /*
     * --------------------------------------------------------------------------
     * Tipos de unidade
     * --------------------------------------------------------------------------
     *
     * Carregamos os vínculos com as filiais para que o JavaScript
     * consiga filtrar os tipos de unidade conforme a filial escolhida.
     */

        $unitTypes = UnitType::query()
            ->where('active', true)
            ->with('branches:id')
            ->orderBy('name')
            ->get();

        /*
     * --------------------------------------------------------------------------
     * Perfis operacionais
     * --------------------------------------------------------------------------
     *
     * Cada perfil pertence a um tipo de unidade.
     */

        $operationalProfiles = OperationalProfile::query()
            ->where('active', true)
            ->orderBy('name')
            ->get();

        /*
     * --------------------------------------------------------------------------
     * Dados para o JavaScript
     * --------------------------------------------------------------------------
     */

        $unitTypesData = $unitTypes
            ->map(function (UnitType $unitType) {
                return [
                    'id' => $unitType->id,
                    'name' => $unitType->name,
                    'branch_ids' => $unitType->branches
                        ->pluck('id')
                        ->values()
                        ->all(),
                    'active' => $unitType->active,
                ];
            })
            ->values()
            ->all();

        $operationalProfilesData = $operationalProfiles
            ->map(function (OperationalProfile $profile) {
                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'unit_type_id' => $profile->unit_type_id,
                    'active' => $profile->active,
                ];
            })
            ->values()
            ->all();

        /*
     * --------------------------------------------------------------------------
     * View
     * --------------------------------------------------------------------------
     *
     * A mesma página possui os dois formulários.
     */

        return view(
            'configurations.operational-units.create',
            [
                'branches' => $branches,
                'unitTypes' => $unitTypes,
                'operationalProfiles' => $operationalProfiles,

                'unitTypesData' => $unitTypesData,
                'operationalProfilesData' => $operationalProfilesData,
            ]
        );
    }

    /**
     * Armazena múltiplas unidades operacionais.
     */
    public function storeMultiple(
        StoreMultipleOperationalUnitRequest $request,
        OperationalUnitService $operationalUnitService
    ): RedirectResponse {
        $this->authorize('create', OperationalUnit::class);

        $result = $operationalUnitService->createBatch(
            $request->validated()
        );

        $message = "{$result['created']} unidade(s) operacional(is) criada(s) com sucesso.";

        if ($result['skipped'] > 0) {
            $message .= " {$result['skipped']} unidade(s) não foram criadas porque já existem na filial.";

            if ($result['identifiers'] !== []) {
                $message .= ' Já existentes: '
                    . implode(', ', $result['identifiers'])
                    . '.';
            }
        }

        return redirect()
            ->route('configuracoes.unidades-operacionais.index')
            ->with('success', $message);
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(
        OperationalUnit $operationalUnit
    ): View {
        $this->authorize('update', $operationalUnit);

        /*
         * Carrega os relacionamentos atuais.
         */
        $operationalUnit->load([
            'branch',
            'unitType',
            'operationalProfile',
        ]);

        /*
         * Filiais ativas.
         *
         * A filial atual também é mantida caso tenha sido
         * inativada depois que a unidade foi cadastrada.
         */
        $branches = Branch::query()
            ->where(function ($query) use ($operationalUnit) {
                $query
                    ->where('active', true)
                    ->orWhere(
                        'id',
                        $operationalUnit->branch_id
                    );
            })
            ->orderBy('name')
            ->get();

        /*
         * Tipos de unidade.
         *
         * Somente tipos:
         *
         * - ativos;
         * - vinculados à filial atual.
         *
         * O tipo atualmente utilizado também é mantido,
         * mesmo que esteja inativo.
         */
        $unitTypes = UnitType::query()
            ->where(function ($query) use ($operationalUnit) {
                $query
                    ->where('active', true)
                    ->orWhere(
                        'id',
                        $operationalUnit->unit_type_id
                    );
            })
            ->whereHas('branches', function ($query) use ($operationalUnit) {
                $query->where(
                    'branches.id',
                    $operationalUnit->branch_id
                );
            })
            ->with('branches:id')
            ->orderBy('name')
            ->get();

        /*
         * Perfis pertencentes ao tipo atual.
         *
         * O perfil atualmente utilizado também é mantido,
         * mesmo que esteja inativo.
         */
        $operationalProfiles = OperationalProfile::query()
            ->where(
                'unit_type_id',
                $operationalUnit->unit_type_id
            )
            ->where(function ($query) use ($operationalUnit) {
                $query
                    ->where('active', true)
                    ->orWhere(
                        'id',
                        $operationalUnit->operational_profile_id
                    );
            })
            ->orderBy('name')
            ->get();

        /*
         * Estrutura consumida pelo operationalUnit.js.
         *
         * No edit, os tipos já estão limitados à filial atual.
         */
        $unitTypesData = $unitTypes
            ->map(function (UnitType $unitType) {
                return [
                    'id' => $unitType->id,
                    'name' => $unitType->name,
                    'branch_ids' => $unitType->branches
                        ->pluck('id')
                        ->values()
                        ->all(),
                    'active' => $unitType->active,
                ];
            })
            ->values()
            ->all();

        $operationalProfilesData = $operationalProfiles
            ->map(function (OperationalProfile $profile) {
                return [
                    'id' => $profile->id,
                    'name' => $profile->name,
                    'unit_type_id' => $profile->unit_type_id,
                    'active' => $profile->active,
                ];
            })
            ->values()
            ->all();

        return view(
            'configurations.operational-units.edit',
            [
                'operationalUnit' => $operationalUnit,
                'branches' => $branches,
                'unitTypes' => $unitTypes,
                'operationalProfiles' => $operationalProfiles,
                'unitTypesData' => $unitTypesData,
                'operationalProfilesData' => $operationalProfilesData,
            ]
        );
    }

    /**
     * Atualiza uma unidade operacional.
     */
    public function update(
        UpdateOperationalUnitRequest $request,
        OperationalUnit $operationalUnit
    ): RedirectResponse {
        $this->authorize('update', $operationalUnit);

        $validated = $request->validated();

        $operationalUnit->update($validated);

        return redirect()
            ->route('configuracoes.unidades-operacionais.index')
            ->with(
                'success',
                'Unidade operacional atualizada com sucesso.'
            );
    }

    /**
     * Inativa uma unidade operacional.
     */
    public function destroy(
        OperationalUnit $operationalUnit
    ): RedirectResponse {
        $this->authorize('toggleActive', $operationalUnit);

        $operationalUnit->update([
            'active' => false,
        ]);

        return redirect()
            ->route('configuracoes.unidades-operacionais.index')
            ->with(
                'success',
                'Unidade operacional inativada com sucesso.'
            );
    }

    /**
     * Ativa uma unidade operacional.
     */
    public function activate(
        OperationalUnit $operationalUnit
    ): RedirectResponse {
        $this->authorize('toggleActive', $operationalUnit);

        $operationalUnit->update([
            'active' => true,
        ]);

        return redirect()
            ->route('configuracoes.unidades-operacionais.index')
            ->with(
                'success',
                'Unidade operacional ativada com sucesso.'
            );
    }
}
