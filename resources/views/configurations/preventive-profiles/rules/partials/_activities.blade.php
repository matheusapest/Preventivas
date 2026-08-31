<div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">

    <div class="border-b border-slate-200 px-6 py-4">
        <h2 class="text-lg font-semibold text-slate-800">
            Atividades
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Atividades configuradas para esta regra.
        </p>
    </div>

    <div class="px-6 py-6">

        @if ($rule->activities->isNotEmpty())

            @php
                $activitiesByCategory = $rule->activities
                    ->filter(fn ($ruleActivity) => $ruleActivity->activity)
                    ->groupBy(
                        fn ($ruleActivity) =>
                            $ruleActivity->activity->activity_category_id
                    );
            @endphp

            <div class="space-y-4">

                @foreach ($activitiesByCategory as $categoryId => $categoryActivities)

                    @php
                        $category = $categoryActivities
                            ->first()
                            ->activity
                            ->activityCategory;
                    @endphp

                    <div class="overflow-hidden rounded-lg border border-slate-200">

                        {{-- CATEGORIA --}}
                        <div class="border-b border-slate-200 bg-slate-50 px-4 py-3">
                            <h3 class="text-sm font-semibold text-slate-700">
                                {{ $category?->name ?? 'Sem categoria' }}
                            </h3>
                        </div>

                        {{-- ATIVIDADES --}}
                        <div class="divide-y divide-slate-100 bg-white">

                            @foreach ($categoryActivities as $ruleActivity)

                                <div class="flex items-center px-4 py-3">

                                    <span class="text-sm font-medium text-slate-900">
                                        {{ $ruleActivity->activity?->name ?? '-' }}
                                    </span>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-sm text-slate-500">
                Nenhuma atividade configurada para esta regra.
            </p>

        @endif

    </div>

</div>
