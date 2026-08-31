@extends('layout.app')

@section('title', 'Tipos de Unidade')

@section('content')

    <div class="space-y-6">

        {{-- PAGE HEADER --}}
        <x-layout.page-header
            title="Tipos de Unidade"
            description="Gerencie os tipos de unidades operacionais disponíveis no sistema."
        >
            <x-slot:breadcrumb>
                Dashboard / Configurações / Tipos de Unidade
            </x-slot:breadcrumb>

            <x-slot:actions>

                @can('create', App\Models\Configuration\Operational\UnitType::class)

                    <div class="w-full sm:w-auto">

                        <x-buttons.primary
                            :href="route('configuracoes.tipos-unidade.create')"
                            class="w-full justify-center sm:w-auto"
                        >
                            Novo Tipo de Unidade
                        </x-buttons.primary>

                    </div>

                @endcan

            </x-slot:actions>

        </x-layout.page-header>


        {{-- CONTAINER DE DADOS --}}
        <x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- ========================================================= --}}
            {{-- VISÃO MOBILE                                              --}}
            {{-- ========================================================= --}}

            <div class="divide-y divide-slate-200 md:hidden">

                @forelse ($unitTypes as $unitType)

                    <div class="space-y-3 p-4">

                        {{-- Nome --}}
                        <div>

                            <div class="flex items-center justify-between gap-2">

                                <span class="truncate text-base font-bold text-slate-900">
                                    {{ $unitType->name }}
                                </span>

                            </div>

                        </div>


                        {{-- Filiais --}}
                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Filiais
                            </p>

                            <div class="mt-1 flex flex-wrap gap-1.5">

                                @forelse ($unitType->branches as $branch)

                                    <span
                                        class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700"
                                    >
                                        {{ $branch->name }}
                                    </span>

                                @empty

                                    <span class="text-xs text-slate-400">
                                        Nenhuma filial vinculada
                                    </span>

                                @endforelse

                            </div>

                        </div>


                        {{-- Status + Ações --}}
                        <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-2">

                            {{-- Status --}}
                            <div>

                                @if ($unitType->active)

                                    <x-badges.success>
                                        Ativo
                                    </x-badges.success>

                                @else

                                    <x-badges.danger>
                                        Inativo
                                    </x-badges.danger>

                                @endif

                            </div>


                            {{-- Ações --}}
                            <div class="flex gap-2">

                                @can('update', $unitType)

                                    <x-buttons.warning
                                        :href="route('configuracoes.tipos-unidade.edit', $unitType)"
                                        class="text-xs py-2"
                                    >
                                        Editar
                                    </x-buttons.warning>

                                @endcan


                                @can('update', $unitType)

                                    @if ($unitType->active)

                                        <form
                                            action="{{ route('configuracoes.tipos-unidade.destroy', $unitType) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <x-buttons.danger type="submit">
                                                Inativar
                                            </x-buttons.danger>

                                        </form>

                                    @else

                                        <form
                                            action="{{ route('configuracoes.tipos-unidade.activate', $unitType) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <x-buttons.success type="submit">
                                                Ativar
                                            </x-buttons.success>

                                        </form>

                                    @endif

                                @endcan

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="p-6 text-center text-sm text-slate-500">
                        Nenhum tipo de unidade cadastrado.
                    </div>

                @endforelse

            </div>


            {{-- ========================================================= --}}
            {{-- VISÃO DESKTOP                                             --}}
            {{-- ========================================================= --}}

            <div class="hidden max-w-full overflow-x-hidden md:block md:overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Tipo de Unidade
                            </th>

                            <th
                                scope="col"
                                class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500"
                            >
                                Filiais
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

                        @forelse ($unitTypes as $unitType)

                            <tr class="transition hover:bg-slate-50/80">

                                {{-- Nome --}}
                                <td class="px-6 py-4 font-medium text-slate-900">

                                    {{ $unitType->name }}

                                </td>


                                {{-- Filiais --}}
                                <td class="px-6 py-4">

                                    <div class="flex max-w-md flex-wrap gap-1.5">

                                        @forelse ($unitType->branches as $branch)

                                            <span
                                                class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700"
                                            >
                                                {{ $branch->name }}
                                            </span>

                                        @empty

                                            <span class="text-xs text-slate-400">
                                                Nenhuma filial vinculada
                                            </span>

                                        @endforelse

                                    </div>

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">

                                    @if ($unitType->active)

                                        <x-badges.success>
                                            Ativo
                                        </x-badges.success>

                                    @else

                                        <x-badges.danger>
                                            Inativo
                                        </x-badges.danger>

                                    @endif

                                </td>


                                {{-- Ações --}}
                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        @can('update', $unitType)

                                            <x-buttons.warning
                                                :href="route('configuracoes.tipos-unidade.edit', $unitType)"
                                            >
                                                Editar
                                            </x-buttons.warning>

                                        @endcan


                                        @can('update', $unitType)

                                            @if ($unitType->active)

                                                <form
                                                    action="{{ route('configuracoes.tipos-unidade.destroy', $unitType) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <x-buttons.danger type="submit">
                                                        Inativar
                                                    </x-buttons.danger>

                                                </form>

                                            @else

                                                <form
                                                    action="{{ route('configuracoes.tipos-unidade.activate', $unitType) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <x-buttons.success type="submit">
                                                        Ativar
                                                    </x-buttons.success>

                                                </form>

                                            @endif

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
                                    Nenhum tipo de unidade cadastrado.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINAÇÃO --}}
            @if (method_exists($unitTypes, 'hasPages') && $unitTypes->hasPages())

                <div class="border-t border-slate-200 px-4 py-3 sm:px-6">

                    {{ $unitTypes->links() }}

                </div>

            @endif

        </x-cards.card>

    </div>

@endsection
