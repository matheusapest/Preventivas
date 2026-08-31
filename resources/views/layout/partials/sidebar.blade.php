@php
    // Estilos padrão reaproveitados nos subitens
    $linkClass = 'flex items-center gap-2.5 rounded-lg px-3 py-2 text-xs font-medium transition';
    $activeClass = 'bg-blue-600/20 text-blue-400 font-semibold';
    $inactiveClass = 'text-slate-400 hover:bg-slate-800/60 hover:text-white';
@endphp

<aside class="flex h-screen w-64 flex-col bg-slate-900 border-r border-slate-800 shrink-0">

    {{-- 1. HEADER DA SIDEBAR / LOGO PRÓPRIO --}}
    <div class="flex h-16 shrink-0 items-center gap-3 px-5 border-b border-slate-800">

        {{-- Importação da Imagem do Logo Local em public/images/preventivas.png --}}
        <img
            src="{{ asset('images/preventivas.png') }}"
            alt="Preventivas Logo"
            class="h-9 w-auto max-w-[40px] object-contain rounded-lg"
        >

        {{-- Texto da Marca --}}
        <div class="flex flex-col">
            <span class="text-base font-bold tracking-tight text-white leading-none">
                Preventivas<span class="text-blue-500">.</span>
            </span>
            <span class="text-[10px] font-medium tracking-wider text-slate-400 uppercase mt-1">
                Sistema de Gestão de Ativos
            </span>
        </div>
    </div>

    {{-- 2. NAVEGAÇÃO PRINCIPAL (Scrollbar interna) --}}
    <nav
        class="
            min-h-0
            flex-1
            flex
            flex-col
            overflow-y-auto
            px-3
            py-4
            space-y-2.5

            /* Customização da Scrollbar Local */
            [&::-webkit-scrollbar]:w-2.5
            [&::-webkit-scrollbar-track]:bg-slate-900/50
            [&::-webkit-scrollbar-thumb]:bg-slate-700
            [&::-webkit-scrollbar-thumb]:rounded-full
            hover:[&::-webkit-scrollbar-thumb]:bg-slate-500
        "
    >
        {{-- Módulos da Aplicação --}}
        @include('layout.partials.sidebar.dashboard')
        @include('layout.partials.sidebar.cadastros')
        @include('layout.partials.sidebar.ativos')
        @include('layout.partials.sidebar.preventivas')
        @include('layout.partials.sidebar.relatorios')
        @include('layout.partials.sidebar.seguranca')

        {{-- 3. CARD DE SUPORTE (Empurrado para o fim com mt-auto) --}}
        <div class="mt-auto pt-6 pb-2">
            <div class="rounded-xl bg-slate-800/50 p-3.5 border border-slate-700/50 transition hover:bg-slate-800/80">
                <div class="flex items-center gap-2 text-slate-300 font-medium text-xs">
                    <span>❓ Precisa de um help </span>
                </div>
                <p class="mt-1 text-[11px] text-slate-400 leading-relaxed">
                    Precisa de ajuda ou encontrou um problema?
                </p>
                <a href="suporteti.mastersonda.com.br" class="mt-2.5 inline-flex items-center gap-1 text-xs font-semibold text-blue-400 hover:text-blue-300 transition">
                    <span>Abrir Chamado</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </nav>

</aside>
