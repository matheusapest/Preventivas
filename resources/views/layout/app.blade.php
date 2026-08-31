<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="h-full bg-slate-100 font-sans text-slate-800 antialiased overflow-x-hidden">

    <div class="flex min-h-screen bg-slate-100 w-full min-w-0">

        {{-- BACKDROP / SOMBRA NO MOBILE --}}
        <div id="sidebar-backdrop"
            class="fixed inset-0 z-40 hidden bg-slate-900/60 backdrop-blur-sm transition-opacity lg:hidden"></div>

        {{-- SIDEBAR (Ajustada para shrink-0 e lg:sticky para travar o layout) --}}
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 -translate-x-full transform transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 shrink-0 w-64">
            @include('layout.partials.sidebar')
        </aside>

        {{-- ÁREA PRINCIPAL (Garantida com min-w-0 e w-full) --}}
        <div class="flex flex-1 flex-col min-w-0 w-full bg-slate-100">

            {{-- TOPBAR --}}
            <header class="sticky top-0 z-30 w-full shrink-0">
                @include('layout.partials.topbar')
            </header>

            {{-- CONTEÚDO PRINCIPAL (Adicionado min-w-0 e max-w-full) --}}
            <main class="w-full max-w-full flex-1 p-4 sm:p-6 lg:p-8 min-w-0">
                @yield('content')
            </main>
        </div>

    </div>

</body>

</html>
