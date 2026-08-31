{{-- ========================================================= --}}
{{-- REGRA TODOS                                               --}}
{{-- ========================================================= --}}

<div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-5">
        <h2 class="text-base font-semibold text-slate-900">
            Regra Todos
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Estas atividades serão aplicadas como regra padrão
            para todas as unidades da filial.
        </p>
    </div>

    <div class="px-6 py-5">

        <label class="mb-3 block text-sm font-medium text-slate-700">
            Atividades da regra
        </label>

        <div class="overflow-hidden rounded-lg border border-slate-200">

            @forelse ($activityCategories as $category)

                @if ($category->activities->isNotEmpty())

                    {{-- CATEGORIA --}}
                    <div class="border-b border-slate-200 last:border-b-0">

                        <div class="bg-slate-50 px-4 py-2.5">
                            <p class="text-sm font-semibold text-slate-700">
                                {{ $category->name }}
                            </p>
                        </div>

                        {{-- ATIVIDADES DA CATEGORIA --}}
                        <div>
                            @foreach ($category->activities as $activity)

                                <label
                                    class="flex cursor-pointer items-center gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0 hover:bg-slate-50"
                                >

                                    <input
                                        type="checkbox"
                                        name="activity_ids[]"
                                        value="{{ $activity->id }}"
                                        @checked(
                                            in_array(
                                                (int) $activity->id,
                                                $selectedActivityIds ?? [],
                                                true
                                            )
                                        )
                                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    >

                                    <span class="text-sm font-medium text-slate-700">
                                        {{ $activity->name }}
                                    </span>

                                </label>

                            @endforeach
                        </div>

                    </div>

                @endif

            @empty

                <div class="px-4 py-6 text-center">
                    <p class="text-sm text-slate-500">
                        Nenhuma atividade ativa disponível.
                    </p>
                </div>

            @endforelse

        </div>

        @error('activity_ids')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

        @error('activity_ids.*')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>
