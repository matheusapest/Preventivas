<div
    id="transfer-form"
    class="hidden rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6"
>
    {{-- Cabeçalho --}}
    <div class="border-b border-slate-100 pb-3 sm:pb-4">
        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
            Dados da Transferência
        </h2>
        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
            Confirme a origem do equipamento e selecione a filial de destino.
        </p>
    </div>

    <form
        action="{{ route('transferencias.store') }}"
        method="POST"
        class="mt-4 space-y-4 sm:mt-5 sm:space-y-5"
    >
        @csrf

        <input type="hidden" id="equipment_id" name="equipment_id">

        {{-- Origem --}}
        <div class="rounded-lg border border-blue-100 bg-blue-50/70 p-3.5 sm:p-4">
            <div class="mb-2.5 flex items-center gap-2">
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">1</span>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-blue-900 sm:text-sm">
                    Origem Confirmada
                </h3>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                <div>
                    <label class="text-xs font-medium text-slate-500">Filial Atual</label>
                    <p id="origin-branch" class="mt-0.5 text-sm font-bold text-slate-800 sm:text-base">—</p>
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-500">Patrimônio</label>
                    <p id="origin-asset-number" class="mt-0.5 text-sm font-bold text-slate-800 sm:text-base">—</p>
                </div>
            </div>
        </div>

        {{-- Destino --}}
        <div>
            <label for="destination_branch_id" class="mb-1.5 block text-xs font-medium text-slate-700 sm:text-sm">
                Filial Destino <span class="text-rose-500">*</span>
            </label>

            <select
                id="destination_branch_id"
                name="destination_branch_id"
                required
                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:py-3 sm:text-base"
            >
                <option value="">Selecione a filial de destino</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Observação --}}
        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="observation" class="block text-xs font-medium text-slate-700 sm:text-sm">
                    Observação
                </label>
                <span class="text-[11px] text-slate-400">Máx. 500 caracteres</span>
            </div>

            <textarea
                id="observation"
                name="observation"
                rows="3"
                maxlength="500"
                class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 sm:text-base"
                placeholder="Informe o motivo da transferência ou detalhes relevantes sobre o estado do equipamento..."
            >{{ old('observation') }}</textarea>
        </div>

        {{-- Resumo da Operação --}}
        <div class="rounded-lg border border-amber-200/80 bg-amber-50/70 p-3.5 sm:p-4">
            <div class="flex items-start gap-2.5">
                <span class="mt-0.5 shrink-0 text-base">📋</span>
                <div>
                    <h4 class="text-xs font-bold text-amber-900 sm:text-sm">
                        Resumo da Operação
                    </h4>
                    <p class="mt-0.5 text-xs text-amber-800 leading-relaxed sm:text-sm">
                        O equipamento será transferido da filial
                        <strong id="summary-origin-branch" class="font-bold text-amber-950">—</strong>
                        para
                        <strong id="summary-destination-branch" class="font-bold text-amber-950">Nenhuma filial selecionada</strong>.
                    </p>
                </div>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="flex flex-col-reverse gap-2.5 pt-2 sm:flex-row sm:justify-end sm:gap-3">
            <button
                type="reset"
                class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50 sm:w-auto sm:py-3"
            >
                Limpar
            </button>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-center text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 active:bg-blue-800 sm:w-auto sm:py-3"
            >
                <svg class="h-4 w-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span>Transferir Equipamento</span>
            </button>
        </div>

    </form>
</div>
