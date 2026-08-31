@extends('layout.app')

@section('title', 'Usuários')

@section('content')

    <x-layout.page-header
        title="Usuários"
        description="Cadastro de usuários do sistema."
    >

        <x-slot:breadcrumb>

            Dashboard /
            Configurações /
            Segurança /
            Usuários

        </x-slot:breadcrumb>

        <x-slot:actions>

            @can('create', App\Models\Access\User::class)

                <x-buttons.primary
                    :href="route('users.create')"
                >
                    Novo Usuário
                </x-buttons.primary>

            @endcan

        </x-slot:actions>

    </x-layout.page-header>

    <x-cards.card>

        <x-tables.table>

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Nome
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            E-mail
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Perfil
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Ações
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-200 bg-white">

                    @forelse ($users as $user)

                        <tr class="hover:bg-slate-50">

                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $user->name }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-4 text-slate-600">
                                {{ $user->role->name }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                @if($user->active)

                                    <x-badges.success>
                                        Ativo
                                    </x-badges.success>

                                @else

                                    <x-badges.danger>
                                        Inativo
                                    </x-badges.danger>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-center gap-2">

                                    @can('update', $user)

                                        <x-buttons.warning
                                            :href="route('users.edit', $user)"
                                        >
                                            Editar
                                        </x-buttons.warning>

                                    @endcan

                                    @can('toggleActive', $user)

                                        <form
                                            action="{{ route('users.toggle-active', $user) }}"
                                            method="POST"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            @if($user->active)

                                                <x-buttons.danger
                                                    type="submit"
                                                >
                                                    Inativar
                                                </x-buttons.danger>

                                            @else

                                                <x-buttons.success
                                                    type="submit"
                                                >
                                                    Ativar
                                                </x-buttons.success>

                                            @endif

                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-8 text-center text-slate-500"
                            >
                                Nenhum usuário encontrado.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </x-tables.table>

        <div class="border-t border-slate-200 px-6 py-4">

            {{ $users->links() }}

        </div>

    </x-cards.card>

@endsection
