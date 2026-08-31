@props([
    'title',
    'description' => null,
])

<div class="mb-4">

    @isset($breadcrumb)

        <nav class="mb-2 text-xs text-slate-500">
            {{ $breadcrumb }}
        </nav>

    @endisset

    <div class="flex items-start justify-between gap-3">

        <div class="min-w-0">

            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                {{ $title }}
            </h1>

            @if ($description)

                <p class="mt-1 text-sm text-slate-500">
                    {{ $description }}
                </p>

            @endif

        </div>

        @isset($actions)

            <div class="shrink-0">
                {{ $actions }}
            </div>

        @endisset

    </div>

</div>
