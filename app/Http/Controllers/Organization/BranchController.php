<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;

use App\Enums\BranchType;
use App\Enums\State;
use App\Http\Requests\Organization\StoreBranchRequest;
use App\Http\Requests\Organization\UpdateBranchRequest;
use App\Models\Organization\Branch;
use App\Models\Organization\BranchCode;
use App\Models\Organization\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchController extends Controller
{
    /**
     * Lista as filiais.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Branch::class);

        $branches = Branch::with([
            'company',
            'branchCode',
        ])
            ->orderBy('name')
            ->paginate(15);

        return view(
            'branches.index',
            compact('branches')
        );
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $this->authorize('create', Branch::class);

        return view(
            'branches.create',
            $this->formData()
        );
    }

    /**
     * Salva uma nova filial.
     */
    public function store(
        StoreBranchRequest $request
    ): RedirectResponse {
        $this->authorize('create', Branch::class);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        Branch::create($validated);

        return redirect()
            ->route('filiais.index')
            ->with(
                'success',
                'Filial cadastrada com sucesso.'
            );
    }

    /**
     * Formulário de edição.
     */
    public function edit(
        Branch $branch
    ): View {
        $this->authorize('update', $branch);

        return view(
            'branches.edit',
            array_merge(
                $this->formData($branch),
                compact('branch')
            )
        );
    }

    /**
     * Atualiza uma filial.
     */
    public function update(
        UpdateBranchRequest $request,
        Branch $branch
    ): RedirectResponse {
        $this->authorize('update', $branch);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        $branch->update($validated);

        return redirect()
            ->route('filiais.index')
            ->with(
                'success',
                'Filial atualizada com sucesso.'
            );
    }

    /**
     * Ativa/Inativa uma filial.
     */
    public function toggleActive(
        Branch $branch
    ): RedirectResponse {
        $this->authorize('toggleActive', $branch);

        $branch->update([
            'active' => ! $branch->active,
        ]);

        return redirect()
            ->route('filiais.index')
            ->with(
                'success',
                $branch->active
                    ? 'Filial ativada com sucesso.'
                    : 'Filial inativada com sucesso.'
            );
    }

    /**
     * Dados utilizados pelos formulários.
     */
    private function formData(
        ?Branch $branch = null
    ): array {
        $companies = Company::active()
            ->group()
            ->orderBy('name')
            ->get();

        $branchCodes = BranchCode::active()
            ->where(function ($query) use ($branch) {
                $query->whereDoesntHave('branch', function ($query) {
                    $query->where('active', true);
                });

                if ($branch) {
                    $query->orWhere(
                        (new BranchCode())->getKeyName(),
                        $branch->branch_code_id
                    );
                }
            })
            ->orderBy('code')
            ->get();

        $states = collect(State::cases())
            ->map(fn (State $state) => (object) [
                'value' => $state->value,
                'label' => $state->label(),
            ]);

        $types = collect(BranchType::cases())
            ->map(fn (BranchType $type) => (object) [
                'value' => $type->value,
                'label' => $type->label(),
            ]);

        return compact(
            'companies',
            'branchCodes',
            'states',
            'types',
        );
    }
}
