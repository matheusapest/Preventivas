@extends('layout.app')

@section('title', 'Consultar Equipamento')

@section('content')

<x-layout.page-header
    title="Consultar Equipamento"
    description="Consulte a situação atual de um equipamento para verificar sua disponibilidade para transferência."
>
    <x-slot:breadcrumb>
        Dashboard /
        Operação /
        Transferências /
        Consultar
    </x-slot:breadcrumb>
</x-layout.page-header>


<div
    class="
        rounded-xl
        border
        border-slate-200
        bg-white
        p-4
        shadow-sm
        md:p-6
    "
>

    <h2
        class="
            mb-4
            text-lg
            font-semibold
            text-slate-900
        "
    >
        Buscar Equipamento
    </h2>


    <form id="consult-equipment-search-form">

        <div
            class="
                grid
                gap-4
                md:grid-cols-[1fr_auto]
            "
        >

            <div>

                <label
                    for="consult-equipment-identifier"
                    class="
                        mb-2
                        block
                        text-sm
                        font-medium
                        text-slate-700
                    "
                >
                    Patrimônio ou Número de Série
                </label>


                <input
                    id="consult-equipment-identifier"
                    name="identifier"
                    type="text"
                    required
                    autocomplete="off"
                    class="
                        w-full
                        rounded-lg
                        border
                        border-slate-300
                        px-4
                        py-3
                        focus:border-blue-500
                        focus:outline-none
                        focus:ring-2
                        focus:ring-blue-500
                    "
                    placeholder="Informe o patrimônio ou número de série"
                >

            </div>


            <div class="flex items-end">

                <button
                    id="consult-btn-search-equipment"
                    type="submit"
                    class="
                        w-full
                        rounded-lg
                        bg-blue-600
                        px-6
                        py-3
                        font-medium
                        text-white
                        transition
                        hover:bg-blue-700
                        disabled:cursor-not-allowed
                        disabled:opacity-60
                        md:w-auto
                    "
                >
                    Buscar
                </button>

            </div>

        </div>

    </form>

</div>


<div
    id="consult-equipment-result"
    class="
        mt-4
        hidden
        rounded-xl
        border
        border-slate-200
        bg-white
        p-4
        shadow-sm
        md:p-6
    "
>

    @include('transfers.partials.equipment-details')

</div>

@endsection

@vite('resources/js/transfers/search.js')
