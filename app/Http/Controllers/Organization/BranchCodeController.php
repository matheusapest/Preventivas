<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;

use App\Http\Requests\Organization\StoreBranchCodeRequest;
use App\Http\Requests\Organization\UpdateBranchCodeRequest;
use App\Models\Organization\BranchCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchCodeController extends Controller
{
    /**
     * Lista os códigos de filiais.
     */
    public function index(): View
    {
        $this->authorize('viewAny', BranchCode::class);

        $branchCodes = BranchCode::orderBy('code')
            ->paginate(15);

        return view(
            'branch-codes.index',
            compact('branchCodes')
        );
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $this->authorize('create', BranchCode::class);

        return view('branch-codes.create');
    }

    /**
     * Cadastra um novo código de filial.
     */
    public function store(
        StoreBranchCodeRequest $request
    ): RedirectResponse {

        $this->authorize('create', BranchCode::class);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        BranchCode::create($validated);

        return redirect()
            ->route('codigos-filiais.index')
            ->with(
                'success',
                'Código de filial cadastrado com sucesso.'
            );
    }

    /**
     * Formulário de edição.
     */
    public function edit(
        BranchCode $branchCode
    ): View {

        $this->authorize('update', $branchCode);

        return view(
            'branch-codes.edit',
            compact('branchCode')
        );
    }

    /**
     * Atualiza um código de filial.
     */
    public function update(
        UpdateBranchCodeRequest $request,
        BranchCode $branchCode
    ): RedirectResponse {

        $this->authorize('update', $branchCode);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        $branchCode->update($validated);

        return redirect()
            ->route('codigos-filiais.index')
            ->with(
                'success',
                'Código de filial atualizado com sucesso.'
            );
    }

    /**
     * Ativa/Inativa um código de filial.
     */
    public function toggleActive(
        BranchCode $branchCode
    ): RedirectResponse {

        $this->authorize(
            'toggleActive',
            $branchCode
        );

        $branchCode->update([
            'active' => ! $branchCode->active,
        ]);

        return redirect()
            ->route('codigos-filiais.index')
            ->with(
                'success',
                $branchCode->active
                    ? 'Código de filial ativado com sucesso.'
                    : 'Código de filial inativado com sucesso.'
            );
    }
}
