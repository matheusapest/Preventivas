@extends('layout.app')

@section('title', 'Empresas')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Empresas"
            description="Cadastro de empresas do sistema."
        >
            <x-slot:breadcrumb>
                Dashboard / Cadastros / Empresas
            </x-slot:breadcrumb>

            <x-slot:actions>
                @can('create', App\Models\Organization\Company::class)
                    <div class="w-full sm:w-auto">
                        <x-buttons.primary
                            :href="route('empresas.create')"
                            class="w-full justify-center sm:w-auto"
                        >
                            Nova Empresa
                        </x-buttons.primary>
                    </div>
                @endcan
            </x-slot:actions>
        </x-layout.page-header>

        {{-- CONTAINER DE DADOS --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- VISÃO MOBILE --}}
            <div class="divide-y divide-slate-200 md:hidden">

                @forelse ($companies as $company)

                    <div class="space-y-3 p-4">

                        {{-- Nome da Empresa --}}
                        <div class="flex items-center justify-between gap-2">
                            <span class="truncate text-base font-bold text-slate-900">
                                {{ $company->name }}
                            </span>
                        </div>

                        {{-- Tipo da Empresa --}}
                        <div class="text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">
                                Tipo:
                            </span>

                            {{ $company->type->label() }}
                        </div>

                        {{-- Ações no Mobile --}}
                        <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-2">

                            @can('update', $company)
                                <x-buttons.warning
                                    :href="route('empresas.edit', $company)"
                                    class="flex-1 justify-center py-2 text-xs"
                                >
                                    Editar
                                </x-buttons.warning>
                            @endcan

                            @can('toggleActive', $company)

                                <form
                                    action="{{ route('empresas.toggle-active', $company) }}"
                                    method="POST"
                                    id="toggle-form-mobile-{{ $company->id }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <label
                                        class="inline-flex cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 transition active:bg-slate-100"
                                    >
                                        <span
                                            class="text-xs font-semibold {{ $company->active ? 'text-emerald-700' : 'text-slate-500' }}"
                                        >
                                            {{ $company->active ? 'Ativa' : 'Inativa' }}
                                        </span>

                                        <input
                                            type="checkbox"
                                            class="peer sr-only"
                                            {{ $company->active ? 'checked' : '' }}
                                            onchange="document.getElementById('toggle-form-mobile-{{ $company->id }}').submit()"
                                        >

                                        <div
                                            class="peer relative h-5 w-9 rounded-full bg-slate-300 transition-colors duration-200 peer-checked:bg-emerald-500 after:absolute after:start-[2px] after:top-[2px] after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full"
                                        ></div>
                                    </label>

                                </form>

                            @endcan

                        </div>

                    </div>

                @empty

                    <div class="p-6 text-center text-sm text-slate-500">
                        Nenhuma empresa cadastrada.
                    </div>

                @endforelse

            </div>

            {{-- VISÃO DESKTOP --}}
            <div class="hidden max-w-full overflow-x-hidden md:block md:overflow-x-auto bg-red-500">

                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Nome
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Tipo
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Status
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Ações
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white">

                        @forelse ($companies as $company)

                            <tr class="transition hover:bg-slate-50/80">

                                {{-- Nome --}}
                                <td class="px-6 py-4 font-medium text-slate-900">
                                    {{ $company->name }}
                                </td>

                                {{-- Tipo --}}
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $company->type->label() }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">

                                    @if ($company->active)
                                        <x-badges.success>
                                            Ativa
                                        </x-badges.success>
                                    @else
                                        <x-badges.danger>
                                            Inativa
                                        </x-badges.danger>
                                    @endif

                                </td>

                                {{-- Ações --}}
                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        @can('update', $company)
                                            <x-buttons.warning
                                                :href="route('empresas.edit', $company)"
                                            >
                                                Editar
                                            </x-buttons.warning>
                                        @endcan

                                        @can('toggleActive', $company)

                                            <form
                                                action="{{ route('empresas.toggle-active', $company) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                @if ($company->active)

                                                    <x-buttons.danger type="submit">
                                                        Inativar
                                                    </x-buttons.danger>

                                                @else

                                                    <x-buttons.success type="submit">
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
                                    colspan="4"
                                    class="px-6 py-8 text-center text-slate-500"
                                >
                                    Nenhuma empresa cadastrada.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINAÇÃO --}}
            @if ($companies->hasPages())

                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">
                    {{ $companies->links() }}
                </div>

            @endif

        </x-cards.card>

    </div>

@endsection
