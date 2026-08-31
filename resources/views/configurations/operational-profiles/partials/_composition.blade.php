@if ($operationalProfile->categories->isNotEmpty())

    <div class="flex flex-wrap gap-1.5">

        @foreach ($operationalProfile->categories as $profileCategory)

            <span
                class="inline-flex items-center gap-1.5 rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10"
                title="{{ $profileCategory->category->name }}"
            >

                <span>{{ $profileCategory->category->name }}</span>

                <span class="text-blue-400/80 font-normal">×</span>

                <span class="font-bold text-blue-900">{{ $profileCategory->quantity }}</span>

            </span>

        @endforeach

    </div>

@else

    <span class="text-xs font-medium text-slate-400">
        Nenhuma categoria definida
    </span>

@endif
