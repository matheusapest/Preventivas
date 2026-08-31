@extends('layout.app')

@section('content')

    <div class="mx-auto w-full max-w-2xl px-4 py-6">

        {{-- ============================================================
             CABEÇALHO
        ============================================================= --}}

        <div class="mb-6">

            <h1 class="text-xl font-semibold text-gray-900">
                {{ $snapshotRuleActivity->name }}
            </h1>

            @if ($snapshotRuleActivity->description)

                <p class="mt-2 text-sm leading-6 text-gray-600">
                    {{ $snapshotRuleActivity->description }}
                </p>

            @endif

        </div>


        {{-- ============================================================
             FORMULÁRIO
        ============================================================= --}}

        <form
            method="POST"
            action="{{ route('preventivas.execucao.activity.store', [
                'preventive' => $preventive->id,
                'cycleUnit' => $cycleUnit->id,
                'activity' => $snapshotRuleActivity->id,
            ]) }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >

            @csrf


            {{-- ========================================================
                 EVIDÊNCIA FOTOGRÁFICA
            ========================================================= --}}

            <div>

                <div class="mb-3">

                    <label
                        for="photo"
                        class="block text-sm font-semibold text-gray-900"
                    >
                        Evidência fotográfica
                    </label>

                    <p class="mt-1 text-sm leading-5 text-gray-500">
                        Tire uma foto para registrar a evidência desta atividade.
                    </p>

                </div>


                {{-- ====================================================
                     ÁREA DE CAPTURA
                ===================================================== --}}

                <label
                    for="photo"
                    class="flex min-h-48 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-8 text-center transition hover:border-indigo-400 hover:bg-indigo-50/30 active:bg-indigo-50"
                >

                    {{-- ÍCONE --}}

                    <div
                        class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-gray-200"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-7 w-7 text-gray-600"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.75 7.5h.75A2.25 2.25 0 0 0 9.75 5.25v-.75h4.5v.75A2.25 2.25 0 0 0 16.5 7.5h.75A2.25 2.25 0 0 1 19.5 9.75v7.5a2.25 2.25 0 0 1-2.25 2.25h-10.5A2.25 2.25 0 0 1 4.5 17.25v-7.5A2.25 2.25 0 0 1 6.75 7.5Z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 13.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                            />

                        </svg>

                    </div>


                    <span class="text-sm font-semibold text-gray-800">
                        Tirar foto
                    </span>

                    <span class="mt-1 text-xs text-gray-500">
                        Toque aqui para abrir a câmera
                    </span>


                    {{-- INPUT REAL --}}

                    <input
                        id="photo"
                        type="file"
                        name="photo"
                        accept="image/*"
                        capture="environment"
                        required
                        class="sr-only"
                    >

                </label>


                {{-- ====================================================
                     PREVIEW
                ===================================================== --}}

                <div
                    id="photo-preview-container"
                    class="mt-4 hidden overflow-hidden rounded-xl border border-gray-200 bg-gray-50"
                >

                    <div class="border-b border-gray-200 bg-white px-4 py-3">

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Foto selecionada
                        </p>

                    </div>


                    <div class="p-3">

                        <img
                            id="photo-preview"
                            src=""
                            alt="Pré-visualização da evidência fotográfica"
                            class="max-h-80 w-full rounded-lg object-contain"
                        >

                    </div>

                </div>


                @error('photo')

                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- ========================================================
                 OBSERVAÇÃO
            ========================================================= --}}

            <div>

                <label
                    for="observation"
                    class="block text-sm font-semibold text-gray-900"
                >
                    Observação
                </label>

                <p class="mt-1 text-sm text-gray-500">
                    Descreva alguma informação adicional, se necessário.
                </p>


                <textarea
                    id="observation"
                    name="observation"
                    rows="4"
                    maxlength="5000"
                    placeholder="Informe uma observação, se necessário."
                    class="mt-3 block w-full rounded-xl border border-gray-300 bg-white px-3 py-3 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                >{{ old('observation') }}</textarea>


                @error('observation')

                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>

                @enderror

            </div>


            {{-- ========================================================
                 AÇÕES
            ========================================================= --}}

            <div class="flex gap-3 pt-2">

                <a
                    href="{{ route('preventivas.execucao.show', [
                        'preventive' => $preventive->id,
                        'cycleUnit' => $cycleUnit->id,
                    ]) }}"
                    class="inline-flex min-h-12 flex-1 items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 active:scale-[0.98]"
                >
                    Voltar
                </a>


                <button
                    type="submit"
                    class="inline-flex min-h-12 flex-1 items-center justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 active:scale-[0.98]"
                >
                    Concluir atividade
                </button>

            </div>

        </form>

    </div>


    {{-- ============================================================
         PREVIEW DA FOTO
    ============================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const photoInput =
                document.getElementById('photo');

            const previewContainer =
                document.getElementById(
                    'photo-preview-container'
                );

            const preview =
                document.getElementById(
                    'photo-preview'
                );


            if (
                !photoInput ||
                !previewContainer ||
                !preview
            ) {
                return;
            }


            photoInput.addEventListener(
                'change',
                function () {

                    const file =
                        photoInput.files?.[0];


                    if (!file) {

                        preview.src = '';

                        previewContainer.classList.add(
                            'hidden'
                        );

                        return;
                    }


                    if (!file.type.startsWith('image/')) {

                        preview.src = '';

                        previewContainer.classList.add(
                            'hidden'
                        );

                        return;
                    }


                    const objectUrl =
                        URL.createObjectURL(file);


                    preview.src =
                        objectUrl;


                    previewContainer.classList.remove(
                        'hidden'
                    );


                    preview.onload =
                        function () {

                            URL.revokeObjectURL(
                                objectUrl
                            );

                        };

                }
            );

        });

    </script>

@endsection
