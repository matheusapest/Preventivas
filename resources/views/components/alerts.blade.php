{{-- Erros de validação --}}
@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v2m0 4h.01M12 3l9 16H3l9-16z" />
            </svg>

            <div>
                <h3 class="font-semibold text-red-800">
                    Foram encontrados erros na validação
                </h3>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

{{-- Mensagem de erro --}}
@if (session('error'))
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v2m0 4h.01M12 3l9 16H3l9-16z" />
            </svg>

            <div>
                <h3 class="font-semibold text-red-800">
                    Atenção
                </h3>

                <p class="mt-1 text-sm text-red-700">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    </div>
@endif

{{-- Mensagem de sucesso --}}
@if (session('success'))
    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="mt-0.5 h-5 w-5 flex-shrink-0 text-green-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7" />
            </svg>

            <div>
                <h3 class="font-semibold text-green-800">
                    Sucesso
                </h3>

                <p class="mt-1 text-sm text-green-700">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
@endif
