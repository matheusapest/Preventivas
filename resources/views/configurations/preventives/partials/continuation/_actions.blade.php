{{-- ============================================================
     AÇÕES DA CONTINUIDADE
============================================================= --}}

<form
    method="POST"
    action="{{ route('preventivas.continuation.store', $preventive) }}"
    id="continuation-form"
>

    @csrf

    <div
        id="continuation-form-error"
        class="mb-4 hidden rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-700 sm:p-4 sm:text-sm"
    ></div>

    <div class="flex flex-col-reverse gap-2.5 border-t border-slate-200 pt-4 sm:flex-row sm:items-center sm:justify-end sm:gap-3 sm:pt-5">

        <a
            href="{{ route('preventivas.show', $preventive) }}"
            class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 active:scale-[0.98] sm:w-auto sm:text-sm"
        >
            Cancelar
        </a>

        <button
            type="submit"
            id="submit-continuation"
            class="inline-flex w-full items-center justify-center rounded-lg bg-slate-800 px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-slate-700 active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto sm:text-sm"
            disabled
        >
            Criar novo Ciclo
        </button>

    </div>

</form>
