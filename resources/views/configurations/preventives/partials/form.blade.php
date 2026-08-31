<form action="{{ $formAction }}" method="POST" id="preventive-form" class="space-y-8 p-4 sm:p-6 lg:p-8">
    @csrf

    @if (($formMethod ?? 'POST') !== 'POST')
        @method($formMethod)
    @endif

    {{-- ============================================================
        SEÇÃO 1: PARÂMETROS DE CRIAÇÃO
    ============================================================= --}}
    <div class="space-y-5">
        <div class="flex items-start gap-3 border-b border-slate-100 pb-4">
            <span class="mt-0.5 flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6 3.75A2.75 2.75 0 0 1 8.75 1h2.5A2.75 2.75 0 0 1 14 3.75v.443c.795.077 1.584.176 2.365.298a.75.75 0 1 1-.23 1.482l-.149-.022-.841 10.518A2.75 2.75 0 0 1 12.4 19H7.6a2.75 2.75 0 0 1-2.745-2.53L4.014 5.95l-.149.023a.75.75 0 0 1-.23-1.482 41.03 41.03 0 0 1 2.365-.298V3.75Zm1.5.418c.803-.075 1.62-.113 2.5-.113s1.697.038 2.5.113V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.418ZM6.5 8a.75.75 0 0 1 .75.75v6.5a.75.75 0 0 1-1.5 0v-6.5A.75.75 0 0 1 6.5 8Zm3.75 0a.75.75 0 0 0-.75.75v6.5a.75.75 0 0 0 1.5 0v-6.5a.75.75 0 0 0-.75-.75Zm2.25.75a.75.75 0 0 1 1.5 0v6.5a.75.75 0 0 1-1.5 0v-6.5Z" clip-rule="evenodd" />
                </svg>
            </span>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">
                    Informações da execução
                </h3>
                <p class="mt-0.5 text-xs text-slate-500">
                    Defina a localização, perfil de regras, responsável e a data para início dos trabalhos.
                </p>
            </div>
        </div>

        {{-- GRID PRINCIPAL DE SELEÇÃO --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

            {{-- FILIAL --}}
            <div>
                <label for="branch_id" class="flex items-center gap-1 text-xs font-semibold text-slate-600">
                    Filial <span class="text-rose-500">*</span>
                </label>
                <select name="branch_id" id="branch_id" required
                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition duration-150 hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                    <option value="">Selecione uma filial</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(old('branch_id', $preventive?->branch_id) == $branch->id)>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TIPO DE PREVENTIVA --}}
            <div>
                <label for="preventive_type_id" class="flex items-center gap-1 text-xs font-semibold text-slate-600">
                    Tipo de preventiva <span class="text-rose-500">*</span>
                </label>
                <select name="preventive_type_id" id="preventive_type_id" required disabled
                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition duration-150 hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 disabled:cursor-not-allowed disabled:border-slate-100 disabled:bg-slate-50 disabled:text-slate-400 disabled:shadow-none">
                    <option value="">Selecione a filial primeiro</option>
                </select>
            </div>

            {{-- PERFIL DE PREVENTIVA --}}
            <div>
                <label for="preventive_profile_id" class="flex items-center gap-1 text-xs font-semibold text-slate-600">
                    Perfil <span class="text-rose-500">*</span>
                </label>
                <select name="preventive_profile_id" id="preventive_profile_id" required disabled
                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition duration-150 hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 disabled:cursor-not-allowed disabled:border-slate-100 disabled:bg-slate-50 disabled:text-slate-400 disabled:shadow-none">
                    <option value="">Selecione o tipo primeiro</option>
                </select>
            </div>

            {{-- RESPONSÁVEL --}}
            <div>
                <label for="assigned_user_id" class="flex items-center gap-1 text-xs font-semibold text-slate-600">
                    Responsável <span class="text-rose-500">*</span>
                </label>
                <select name="assigned_user_id" id="assigned_user_id" required
                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition duration-150 hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                    <option value="">Selecione o responsável</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('assigned_user_id', $preventive?->assigned_user_id) == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- DATA DE INÍCIO --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <label for="start_date" class="flex items-center gap-1 text-xs font-semibold text-slate-600">
                    Data de início <span class="text-rose-500">*</span>
                </label>
                <input type="date" name="start_date" id="start_date" required min="{{ now()->format('Y-m-d') }}"
                    value="{{ old('start_date', $preventive?->start_date?->format('Y-m-d')) }}"
                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm outline-none transition duration-150 hover:border-slate-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                <p id="start-date-help" class="mt-1.5 text-[11px] text-slate-400">Segunda a sábado (domingos não permitidos).</p>
                <p id="start-date-error" class="mt-1.5 hidden text-[11px] font-semibold text-rose-600"></p>
            </div>

        </div>
    </div>


    {{-- ============================================================
        SEÇÃO 2: PAINEL DE CONFIGURAÇÃO (CARREGADO VIA JS)
    ============================================================= --}}
    <div id="profile-configuration" class="hidden overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-b from-slate-50/80 to-white">

        {{-- CABEÇALHO DO PREVIEW --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 bg-white/60 px-4 py-4 sm:px-5">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900">
                        Resumo da configuração carregada
                    </h4>
                    <p id="configuration-description" class="mt-0.5 text-xs text-slate-500">
                        Perfil de preventiva selecionado.
                    </p>
                </div>
            </div>
            <span id="configuration-type"
                class="hidden rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-100"></span>
        </div>


        <div class="space-y-6 p-4 sm:p-5">

            {{-- UNIDADES PARTICIPANTES --}}
            <div>
                <div class="mb-3 flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Unidades elegíveis
                    </span>
                    <span id="units-count" class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">
                        0 unidades
                    </span>
                </div>

                {{-- ESTADO VAZIO --}}
                <div id="units-empty" class="flex flex-col items-center gap-1.5 rounded-xl border border-dashed border-slate-300 bg-white/70 p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.5 3A1.5 1.5 0 0 0 1 4.5v3A1.5 1.5 0 0 0 2.5 9h3A1.5 1.5 0 0 0 7 7.5v-3A1.5 1.5 0 0 0 5.5 3h-3Zm0 8A1.5 1.5 0 0 0 1 12.5v3A1.5 1.5 0 0 0 2.5 17h3A1.5 1.5 0 0 0 7 15.5v-3A1.5 1.5 0 0 0 5.5 11h-3ZM11 4.5A1.5 1.5 0 0 1 12.5 3h3A1.5 1.5 0 0 1 17 4.5v3A1.5 1.5 0 0 1 15.5 9h-3A1.5 1.5 0 0 1 11 7.5v-3Zm1.5 6.5A1.5 1.5 0 0 0 11 12.5v3a1.5 1.5 0 0 0 1.5 1.5h3a1.5 1.5 0 0 0 1.5-1.5v-3A1.5 1.5 0 0 0 15.5 11h-3Z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-xs text-slate-400">
                        Nenhuma unidade operacional configurada para este perfil.
                    </p>
                </div>

                {{-- LISTA DINÂMICA DE UNIDADES --}}
                <div id="units-list" class="hidden grid grid-cols-2 gap-2.5 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                    {{-- O JS vai renderizar os cards com border, bg-white, shadow-sm e p-3 aqui --}}
                </div>
            </div>

            {{-- REGRAS E ATIVIDADES --}}
            <div>
                <div class="mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Regras e checklists associados
                    </span>
                </div>

                {{-- ESTADO VAZIO --}}
                <div id="rules-empty" class="flex flex-col items-center gap-1.5 rounded-xl border border-dashed border-slate-300 bg-white/70 p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 0 1 2-2h6.5a1 1 0 0 1 .707.293l4.5 4.5A1 1 0 0 1 18 7.5V16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4Zm3 8.75a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Zm.75-3.75a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5Z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-xs text-slate-400">
                        Nenhuma regra ou atividade vinculada.
                    </p>
                </div>

                {{-- LISTA DINÂMICA DE REGRAS --}}
                <div id="rules-list" class="hidden space-y-3">
                    {{-- O JS vai renderizar os blocos aqui --}}
                </div>
            </div>

        </div>
    </div>


    {{-- ============================================================
        RODAPÉ / AÇÕES
    ============================================================= --}}
    <div class="flex flex-col-reverse gap-2 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end sm:gap-3">
        <a href="{{ route('preventivas.index') }}"
            class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition duration-150 hover:border-slate-400 hover:bg-slate-50 active:scale-[0.98] sm:w-auto">
            Cancelar
        </a>

        <button type="submit" id="submit-button" disabled
            class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/20 transition duration-150 hover:bg-indigo-500 active:scale-[0.98] disabled:cursor-not-allowed disabled:bg-slate-300 disabled:text-slate-500 disabled:shadow-none sm:w-auto">
            {{ $submitLabel ?? 'Criar preventiva' }}
        </button>
    </div>

</form>
