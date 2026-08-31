{{-- ============================================================
    PREVENTIVAS
============================================================= --}}

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

    {{-- CABEÇALHO --}}
    <div class="border-b border-gray-200 px-4 py-4 sm:px-6">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h2 class="text-base font-semibold text-gray-900">
                    Minhas preventivas
                </h2>

                <p class="text-sm text-gray-500">
                    Preventivas atribuídas a você.
                </p>
            </div>

            <div class="text-xs font-medium text-gray-500 sm:text-sm">
                {{ $preventives->count() }} preventiva(s)
            </div>

        </div>
    </div>


    {{-- =========================================================
        VISUALIZAÇÃO MOBILE / TABLET
    ========================================================== --}}

    <div class="block divide-y divide-gray-200 lg:hidden">

        @forelse ($preventives as $preventive)

            <div class="space-y-3 p-4">

                {{-- ID, TIPO E STATUS --}}
                <div class="flex items-start justify-between gap-2">

                    <div>
                        <span class="text-base font-bold text-gray-900">
                            #{{ $preventive->id }}
                        </span>

                        <span class="mt-0.5 block text-xs text-gray-500">
                            {{ $preventive->preventiveType?->name ?? 'Preventiva' }}
                        </span>
                    </div>

                    <span
                        class="inline-flex shrink-0 rounded-full px-2.5 py-1 text-xs font-medium {{ $preventive->status->colorClass() }}"
                    >
                        {{ $preventive->status->label() }}
                    </span>

                </div>


                {{-- DETALHES --}}
                <div class="grid grid-cols-2 gap-3 border-t border-gray-100 pt-2 text-xs">

                    {{-- FILIAL --}}
                    <div>
                        <span class="block font-semibold uppercase tracking-wider text-gray-400">
                            Filial
                        </span>

                        <span class="block truncate font-medium text-gray-900">
                            {{ $preventive->branch?->name ?? '—' }}
                        </span>
                    </div>


                    {{-- PERFIL --}}
                    <div>
                        <span class="block font-semibold uppercase tracking-wider text-gray-400">
                            Perfil
                        </span>

                        <span class="block truncate font-medium text-gray-900">
                            {{ $preventive->preventiveProfile?->name ?? '—' }}
                        </span>
                    </div>


                    {{-- INÍCIO --}}
                    <div class="col-span-2">

                        <span class="block font-semibold uppercase tracking-wider text-gray-400">
                            Início
                        </span>

                        <span class="font-medium text-gray-900">

                            @if ($preventive->start_at)

                                {{ $preventive->start_at->format('d/m/Y H:i') }}

                            @else

                                {{ $preventive->start_date?->format('d/m/Y') ?? '—' }}

                            @endif

                        </span>

                    </div>

                </div>


                {{-- BOTÃO DE AÇÃO --}}
                <div class="pt-1">

                    @if ($preventive->status->isExecutable())

                        <a
                            href="{{ route('preventivas.execucao.show', $preventive) }}"
                            class="flex w-full items-center justify-center rounded-lg bg-gray-900 py-2.5 text-sm font-medium text-white transition hover:bg-gray-700 active:scale-[0.98]"
                        >
                            Executar
                        </a>

                    @else

                        <a
                            href="{{ route('preventivas.execucao.show', $preventive) }}"
                            class="flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 active:scale-[0.98]"
                        >
                            Visualizar
                        </a>

                    @endif

                </div>

            </div>

        @empty

            <div class="px-4 py-8 text-center">

                <div class="text-sm font-medium text-gray-900">
                    Nenhuma preventiva encontrada
                </div>

                <div class="mt-1 text-xs text-gray-500">
                    Não existem preventivas atribuídas a você.
                </div>

            </div>

        @endforelse

    </div>


    {{-- =========================================================
        VISUALIZAÇÃO DESKTOP
    ========================================================== --}}

    <div class="hidden overflow-x-auto lg:block">

        <table class="w-full border-collapse text-left">

            <thead class="border-b border-gray-200 bg-gray-50">

                <tr>

                    <th
                        scope="col"
                        class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        Preventiva
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        Filial
                    </th>

                    <th
                        scope="col"
                        class="hidden px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 xl:table-cell"
                    >
                        Tipo
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        Perfil
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        Início
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        Status
                    </th>

                    <th
                        scope="col"
                        class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500"
                    >
                        Ação
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-gray-200 bg-white">

                @forelse ($preventives as $preventive)

                    <tr class="transition hover:bg-gray-50/80">

                        {{-- PREVENTIVA --}}
                        <td class="whitespace-nowrap px-4 py-3.5">

                            <div class="font-semibold text-gray-900">
                                #{{ $preventive->id }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $preventive->preventiveType?->name ?? 'Preventiva' }}
                            </div>

                        </td>


                        {{-- FILIAL --}}
                        <td class="whitespace-nowrap px-4 py-3.5 text-sm text-gray-900">
                            {{ $preventive->branch?->name ?? '—' }}
                        </td>


                        {{-- TIPO --}}
                        <td class="hidden whitespace-nowrap px-4 py-3.5 text-sm text-gray-900 xl:table-cell">
                            {{ $preventive->preventiveType?->name ?? '—' }}
                        </td>


                        {{-- PERFIL --}}
                        <td class="whitespace-nowrap px-4 py-3.5 text-sm text-gray-900">
                            {{ $preventive->preventiveProfile?->name ?? '—' }}
                        </td>


                        {{-- INÍCIO --}}
                        <td class="whitespace-nowrap px-4 py-3.5 text-sm text-gray-900">

                            @if ($preventive->start_at)

                                {{ $preventive->start_at->format('d/m/Y H:i') }}

                            @else

                                {{ $preventive->start_date?->format('d/m/Y') ?? '—' }}

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td class="whitespace-nowrap px-4 py-3.5">

                            <span
                                class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $preventive->status->colorClass() }}"
                            >
                                {{ $preventive->status->label() }}
                            </span>

                        </td>


                        {{-- AÇÃO --}}
                        <td class="whitespace-nowrap px-4 py-3.5 text-right">

                            @if ($preventive->status->isExecutable())

                                <a
                                    href="{{ route('preventivas.execucao.show', $preventive) }}"
                                    class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-gray-700"
                                >
                                    Executar
                                </a>

                            @else

                                <a
                                    href="{{ route('preventivas.execucao.show', $preventive) }}"
                                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50"
                                >
                                    Visualizar
                                </a>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="px-6 py-12 text-center">

                            <div class="text-sm font-medium text-gray-900">
                                Nenhuma preventiva encontrada
                            </div>

                            <div class="mt-1 text-xs text-gray-500">
                                Não existem preventivas atribuídas a você.
                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
