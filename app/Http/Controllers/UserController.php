<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Access\Role;
use App\Models\Access\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Lista os usuários.
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = auth()->user()->isAdmin()
            ? User::with('role')
            ->orderBy('name')
            ->paginate(15)
            : User::whereKey(auth()->id())
            ->with('role')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Formulário de cadastro.
     */
    public function create(): View
    {
        $this->authorize('create', User::class);

        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Salva um novo usuário.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        $validated['active'] = $request->boolean('active');

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário cadastrado com sucesso.');
    }

    /**
     * Formulário de edição.
     */
    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        $roles = Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Atualiza um usuário.
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ): RedirectResponse {

        $this->authorize('update', $user);

        $validated = $request->validated();

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        if ($request->user()->isAdmin()) {
            $validated['active'] = $request->boolean('active');
        } else {
            unset($validated['role_id']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Ativa ou inativa um usuário.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        $this->authorize('toggleActive', $user);

        $user->update([
            'active' => ! $user->active,
        ]);

        return redirect()
            ->route('users.index')
            ->with(
                'success',
                $user->active
                    ? 'Usuário ativado com sucesso.'
                    : 'Usuário inativado com sucesso.'
            );
    }
}
