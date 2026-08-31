{{-- ============================================================
     MODAL DE EVIDÊNCIA FOTOGRÁFICA
============================================================= --}}

<div
    id="photo-modal"
    class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 p-3 opacity-0 transition-opacity duration-200 sm:p-6"
    aria-hidden="true"
>
    {{-- BACKDROP --}}
    <div
        data-photo-modal-backdrop
        class="absolute inset-0"
    ></div>

    {{-- CONTEÚDO --}}
    <div
        data-photo-modal-content
        class="relative z-10 flex w-full max-w-6xl max-h-[85vh] scale-95 items-center justify-center transition-transform duration-200 sm:max-h-[90vh]"
    >

        {{-- BOTÃO FECHAR --}}
        <button
            type="button"
            data-photo-modal-close
            class="absolute -right-1 -top-1 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-black/70 text-xl font-medium text-white shadow-lg transition hover:bg-black/90 focus:outline-none focus:ring-2 focus:ring-white sm:-right-3 sm:-top-3 sm:h-9 sm:w-9"
            aria-label="Fechar imagem"
        >
            &times;
        </button>

        {{-- IMAGEM --}}
        <img
            id="photo-modal-image"
            src=""
            alt="Evidência fotográfica"
            class="max-h-[85vh] max-w-full rounded-lg object-contain shadow-2xl sm:max-h-[90vh]"
        >

    </div>
</div>
