<x-cards.card class="rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Filtros
        </h2>

        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
            Utilize os filtros para localizar uma ordem de serviço específica.
        </p>

    </div>


    {{-- Formulário --}}
    <form
        method="GET"
        action="{{ route('reparos_externos.index') }}"
        class="p-4 sm:p-6"
    >

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Busca --}}
            <div class="sm:col-span-2">

                <label
                    for="search"
                    class="block text-xs font-medium text-slate-700 sm:text-sm"
                >
                    Buscar
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="OS, patrimônio, serial ou equipamento"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

            </div>


            {{-- Status --}}
            <div>

                <label
                    for="status"
                    class="block text-xs font-medium text-slate-700 sm:text-sm"
                >
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

                    <option value="">
                        Todos os status
                    </option>

                    @foreach (\App\Enums\MaintenanceOrderStatus::cases() as $orderStatus)

                        <option
                            value="{{ $orderStatus->value }}"
                            @selected($status === $orderStatus->value)
                        >
                            {{ $orderStatus->label() }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Empresa --}}
            <div>

                <label
                    for="company_id"
                    class="block text-xs font-medium text-slate-700 sm:text-sm"
                >
                    Empresa terceirizada
                </label>

                <select
                    id="company_id"
                    name="company_id"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

                    <option value="">
                        Todas as empresas
                    </option>

                    @foreach ($companies as $company)

                        <option
                            value="{{ $company->id }}"
                            @selected((string) $companyId === (string) $company->id)
                        >
                            {{ $company->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Filial --}}
            <div>

                <label
                    for="branch_id"
                    class="block text-xs font-medium text-slate-700 sm:text-sm"
                >
                    Filial de envio
                </label>

                <select
                    id="branch_id"
                    name="branch_id"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

                    <option value="">
                        Todas as filiais
                    </option>

                    @foreach ($branches as $branch)

                        <option
                            value="{{ $branch->id }}"
                            @selected((string) $branchId === (string) $branch->id)
                        >
                            {{ $branch->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Data inicial --}}
            <div>

                <label
                    for="date_from"
                    class="block text-xs font-medium text-slate-700 sm:text-sm"
                >
                    Enviado a partir de
                </label>

                <input
                    type="date"
                    id="date_from"
                    name="date_from"
                    value="{{ $dateFrom }}"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

            </div>


            {{-- Data final --}}
            <div>

                <label
                    for="date_to"
                    class="block text-xs font-medium text-slate-700 sm:text-sm"
                >
                    Enviado até
                </label>

                <input
                    type="date"
                    id="date_to"
                    name="date_to"
                    value="{{ $dateTo }}"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                >

            </div>

        </div>


        {{-- Ações --}}
        <div class="mt-5 flex flex-col gap-2 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end">

            <a
                href="{{ route('reparos_externos.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
            >
                Limpar filtros
            </a>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Filtrar
            </button>

        </div>

    </form>

</x-cards.card>
