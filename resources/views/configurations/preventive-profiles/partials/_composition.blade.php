@if ($preventiveProfile->branches->isNotEmpty())

    <div class="space-y-2">

        @foreach ($preventiveProfile->branches as $profileBranch)

            <div
                class="rounded-lg border border-slate-200 bg-slate-50/70 p-2.5"
            >
                {{-- FILIAL --}}
                <div class="flex items-center justify-between gap-2">

                    <span class="text-xs font-semibold text-slate-700">
                        {{ $profileBranch->branch->name }}
                    </span>

                    <span class="text-[10px] font-medium text-slate-400">
                        Participante
                    </span>

                </div>
            </div>

        @endforeach

    </div>

@else

    <span class="text-xs font-medium text-slate-400">
        Nenhuma filial definida
    </span>

@endif
