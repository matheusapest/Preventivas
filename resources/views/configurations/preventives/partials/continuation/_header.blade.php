{{-- ============================================================
     CABEÇALHO DA CONTINUIDADE
============================================================= --}}

<div class="mb-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <h1 class="text-xl font-semibold text-slate-900">
                Continuidade da Preventiva
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Selecione as unidades e atividades que deverão ser refeitas.
            </p>

        </div>

        <div class="flex items-center gap-2 text-sm text-slate-600">

            <span class="font-medium">
                Preventiva:
            </span>

            <span class="rounded-md bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">
                #{{ $preventive->id }}
            </span>

        </div>

    </div>

</div>


{{-- ============================================================
     ERROS DA CONTINUIDADE
============================================================= --}}

@if ($errors->any())

    <div class="mb-6">

        <x-alerts.error title="Não foi possível criar a continuidade">

            <ul class="list-disc space-y-1 pl-5 text-sm">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </x-alerts.error>

    </div>

@endif
