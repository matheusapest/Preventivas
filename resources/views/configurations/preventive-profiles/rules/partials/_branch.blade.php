{{-- ========================================================= --}}
{{-- FILIAL                                                    --}}
{{-- ========================================================= --}}

<div class="rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">

        <h2 class="text-base font-semibold text-slate-900">
            Filial
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Esta configuração pertence à filial abaixo.
        </p>

    </div>

    <div class="px-6 py-5">

        <div class="rounded-lg bg-slate-50 px-4 py-3">

            <span class="text-sm font-medium text-slate-900">

                {{ $profileBranch?->branch?->name
                    ?? $rule->preventiveProfileBranch?->branch?->name }}

            </span>

        </div>

    </div>

</div>
