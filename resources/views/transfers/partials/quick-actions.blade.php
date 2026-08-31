<x-cards.card class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- Header do Card --}}
    <div class="border-b border-slate-200 px-4 py-3.5 sm:px-6 sm:py-4">
        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Ações Rápidas
        </h2>
    </div>

    {{-- Grid de Ações --}}
    <div class="grid grid-cols-1 gap-4 p-4 sm:gap-6 sm:p-6 md:grid-cols-3">

        {{-- Ação 1: Enviar Equipamento --}}
        <div class="flex flex-col justify-between rounded-lg border border-slate-200 p-4 transition-colors hover:border-slate-300 sm:p-6">
            <div>
                <h3 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Enviar Equipamento
                </h3>

                <p class="mt-1.5 text-sm text-slate-600 sm:mt-2">
                    Inicia uma nova transferência entre filiais.
                </p>
            </div>

            <div class="mt-5 sm:mt-6">
                <x-buttons.primary
                    :href="route('transferencias.create')"
                    class="w-full justify-center sm:w-auto"
                >
                    Acessar
                </x-buttons.primary>
            </div>
        </div>

        {{-- Ação 2: Receber Equipamentos --}}
        <div class="flex flex-col justify-between rounded-lg border border-slate-200 p-4 transition-colors hover:border-slate-300 sm:p-6">
            <div>
                <h3 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Receber Equipamentos
                </h3>

                <p class="mt-1.5 text-sm text-slate-600 sm:mt-2">
                    Confirma o recebimento dos equipamentos enviados.
                </p>
            </div>

            <div class="mt-5 sm:mt-6">
                <x-buttons.primary
                    :href="route('transferencias.receive.index')"
                    class="w-full justify-center sm:w-auto"
                >
                    Acessar
                </x-buttons.primary>
            </div>
        </div>

        {{-- Ação 3: Consultar Equipamento --}}
        <div class="flex flex-col justify-between rounded-lg border border-slate-200 p-4 transition-colors hover:border-slate-300 sm:p-6">
            <div>
                <h3 class="text-base font-semibold text-slate-800 sm:text-lg">
                    Consultar Equipamento
                </h3>

                <p class="mt-1.5 text-sm text-slate-600 sm:mt-2">
                    Consulta um equipamento pelo patrimônio.
                </p>
            </div>

            <div class="mt-5 sm:mt-6">
                <x-buttons.primary
                    :href="route('transferencias.search')"
                    class="w-full justify-center sm:w-auto"
                >
                    Consultar
                </x-buttons.primary>
            </div>
        </div>

    </div>

</x-cards.card>
