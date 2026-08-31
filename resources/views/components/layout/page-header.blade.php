@props([
    'title',
    'description' => null,
])

{{--
    1. flex-col no mobile -> empilha título e ações
    2. sm:flex-row no desktop -> alinha lado a lado
    3. gap-4 -> garante espaço entre eles quando empilhar
--}}
<div class="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-start sm:justify-between">

    <div>

        @isset($breadcrumb)

            <nav class="mb-1 text-xs text-slate-500 sm:mb-2 sm:text-sm">
                {{ $breadcrumb }}
            </nav>

        @endisset

        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
            {{ $title }}
        </h1>

        @if ($description)

            <p class="mt-1 text-sm text-slate-500">
                {{ $description }}
            </p>

        @endif

    </div>

    @isset($actions)

        {{--
            w-full sm:w-auto -> botões ocupam a largura total no mobile
            pt-2 sm:pt-0 -> um leve respiro caso quebre a linha
        --}}
        <div class="flex w-full items-center gap-3 pt-2 sm:w-auto sm:pt-0">

            {{ $actions }}

        </div>

    @endisset

</div>
