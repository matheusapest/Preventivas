<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Header --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">

        <h2 class="text-sm font-semibold text-slate-800 sm:text-base">
            Filtros
        </h2>

        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
            Utilize os filtros para localizar uma preventiva específica.
        </p>

    </div>


    <form
        action="{{ route('preventivas.index') }}"
        method="GET"
        class="p-4 sm:p-5"
    >

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">

            {{-- Busca --}}
            <div class="sm:col-span-2">

                <label
                    for="search"
                    class="block text-xs font-semibold uppercase tracking-wider text-slate-500 sm:text-xs"
                >
                    Buscar
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Preventiva, filial ou responsável"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                >

            </div>


            {{-- Status --}}
            <div>

                <label
                    for="status"
                    class="block text-xs font-semibold uppercase tracking-wider text-slate-500 sm:text-xs"
                >
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                >

                    <option value="">
                        Todos os status
                    </option>

                    @foreach (\App\Enums\StatusPreventiveEnum::cases() as $status)

                        <option
                            value="{{ $status->value }}"
                            @selected(request('status') === $status->value)
                        >
                            {{ $status->label() }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Filial --}}
            <div>

                <label
                    for="branch_id"
                    class="block text-xs font-semibold uppercase tracking-wider text-slate-500 sm:text-xs"
                >
                    Filial
                </label>

                <select
                    id="branch_id"
                    name="branch_id"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                >

                    <option value="">
                        Todas as filiais
                    </option>

                    @foreach ($branches ?? [] as $branch)

                        <option
                            value="{{ $branch->id }}"
                            @selected((string) request('branch_id') === (string) $branch->id)
                        >
                            {{ $branch->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Tipo de Preventiva --}}
            <div>

                <label
                    for="preventive_type_id"
                    class="block text-xs font-semibold uppercase tracking-wider text-slate-500 sm:text-xs"
                >
                    Tipo de preventiva
                </label>

                <select
                    id="preventive_type_id"
                    name="preventive_type_id"
                    class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                >

                    <option value="">
                        Todos os tipos
                    </option>

                    @foreach ($preventiveTypes ?? [] as $preventiveType)

                        <option
                            value="{{ $preventiveType->id }}"
                            @selected((string) request('preventive_type_id') === (string) $preventiveType->id)
                        >
                            {{ $preventiveType->name }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        {{-- Ações --}}
        <div class="mt-4 flex flex-col-reverse gap-2 border-t border-slate-100 pt-4 sm:flex-row sm:justify-end">

            <a
                href="{{ route('preventivas.index') }}"
                class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-[0.98]"
            >
                Limpar filtros
            </a>

            <button
                type="submit"
                class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
                Filtrar
            </button>

        </div>

    </form>

</x-cards.card>
