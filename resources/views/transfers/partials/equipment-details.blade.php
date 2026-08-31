<div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">

    {{-- Cabeçalho --}}
    <div class="border-b border-slate-200 pb-3 sm:pb-4">
        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Informações do Equipamento
        </h2>
        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
            Dados obtidos a partir do patrimônio informado.
        </p>
    </div>

    <div class="mt-4 space-y-5 sm:mt-6 sm:space-y-6">

        {{-- Dados do Equipamento (Grid Responsivo) --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 sm:gap-5">

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm">Patrimônio</label>
                <p id="equipment-asset-number" class="mt-0.5 text-sm font-bold text-slate-800 sm:text-base">—</p>
            </div>

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm">Nome</label>
                <p id="equipment-name" class="mt-0.5 text-sm font-semibold text-slate-800 sm:text-base">—</p>
            </div>

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm">Categoria</label>
                <p id="equipment-category" class="mt-0.5 text-sm text-slate-700">—</p>
            </div>

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm">Fabricante</label>
                <p id="equipment-manufacturer" class="mt-0.5 text-sm text-slate-700">—</p>
            </div>

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm">Modelo</label>
                <p id="equipment-model" class="mt-0.5 text-sm text-slate-700">—</p>
            </div>

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm">Número de Série</label>
                <p id="equipment-serial-number" class="mt-0.5 text-sm font-mono text-slate-700">—</p>
            </div>

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm">Filial Atual</label>
                <p id="equipment-branch" class="mt-0.5 text-sm font-medium text-slate-800">—</p>
            </div>

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm">Status Operacional</label>
                <p id="equipment-operational-status" class="mt-0.5 text-sm font-medium text-slate-800">—</p>
            </div>

            <div>
                <label class="text-xs font-medium text-slate-500 sm:text-sm block mb-0.5">Status</label>
                <p id="equipment-status">
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                        —
                    </span>
                </p>
            </div>

        </div>

        {{-- Última Transferência --}}
        <div class="rounded-lg border border-slate-200/80 bg-slate-50/70 p-3.5 sm:p-4">
            <h3 class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500 sm:text-sm">
                Última Transferência
            </h3>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">
                <div>
                    <label class="text-xs font-medium text-slate-500">Origem</label>
                    <p id="last-origin-branch" class="mt-0.5 text-xs font-semibold text-slate-700 sm:text-sm">—</p>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-500">Destino</label>
                    <p id="last-destination-branch" class="mt-0.5 text-xs font-semibold text-slate-700 sm:text-sm">—</p>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-500">Data</label>
                    <p id="last-transfer-date" class="mt-0.5 text-xs text-slate-600 sm:text-sm">—</p>
                </div>
            </div>
        </div>

        {{-- Situação / Feedback Dynamic Box --}}
        <div id="transfer-status-box" class="rounded-lg border border-blue-200 bg-blue-50/80 p-3.5 sm:p-4 transition-all">
            <div class="flex items-start gap-3">
                <span class="text-lg leading-none shrink-0">ℹ️</span>
                <div class="space-y-0.5">
                    <h4 class="text-xs font-bold text-blue-900 sm:text-sm">
                        Situação
                    </h4>
                    <p id="transfer-status-message" class="text-xs text-blue-800 sm:text-sm leading-relaxed">
                        Informe um patrimônio para consultar o equipamento.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
