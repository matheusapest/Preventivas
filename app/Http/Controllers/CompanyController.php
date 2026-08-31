<?php

namespace App\Http\Controllers;

use App\Enums\CompanyType;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    /**
     * Lista as empresas.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Company::class);

        $companies = Company::query()
            ->orderBy('name')
            ->paginate(10);

        return view(
            'companies.index',
            compact('companies')
        );
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $this->authorize('create', Company::class);

        $types = collect(CompanyType::cases())
            ->map(fn (CompanyType $type) => (object) [
                'value' => $type->value,
                'label' => $type->label(),
            ]);

        return view(
            'companies.create',
            compact('types')
        );
    }

    /**
     * Cadastra uma empresa.
     */
    public function store(
        StoreCompanyRequest $request
    ): RedirectResponse {
        $this->authorize('create', Company::class);

        $validated = $request->validated();

        Company::create($validated);

        return redirect()
            ->route('empresas.index')
            ->with(
                'success',
                'Empresa cadastrada com sucesso.'
            );
    }

    /**
     * Formulário de edição.
     */
    public function edit(
        Company $company
    ): View {
        $this->authorize('update', $company);

        $types = collect(CompanyType::cases())
            ->map(fn (CompanyType $type) => (object) [
                'value' => $type->value,
                'label' => $type->label(),
            ]);

        return view(
            'companies.edit',
            compact(
                'company',
                'types'
            )
        );
    }

    /**
     * Atualiza uma empresa.
     */
    public function update(
        UpdateCompanyRequest $request,
        Company $company
    ): RedirectResponse {
        $this->authorize('update', $company);

        $validated = $request->validated();

        $company->update($validated);

        return redirect()
            ->route('empresas.index')
            ->with(
                'success',
                'Empresa atualizada com sucesso.'
            );
    }

    /**
     * Ativa/Inativa uma empresa.
     */
    public function toggleActive(
        Company $company
    ): RedirectResponse {
        $this->authorize(
            'toggleActive',
            $company
        );

        $company->update([
            'active' => ! $company->active,
        ]);

        return redirect()
            ->route('empresas.index')
            ->with(
                'success',
                $company->active
                    ? 'Empresa ativada com sucesso.'
                    : 'Empresa inativada com sucesso.'
            );
    }
}
