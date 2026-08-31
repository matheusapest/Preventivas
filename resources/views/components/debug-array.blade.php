<div class="rounded-lg border border-yellow-300 bg-yellow-50 p-4">

    @if(isset($title))
        <p class="mb-2 text-xs font-bold uppercase text-yellow-800">
            {{ $title }}
        </p>
    @endif

    <pre class="overflow-auto rounded bg-slate-900 p-4 text-xs text-white">@json($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)</pre>

</div>
