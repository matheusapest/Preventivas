<header class="bg-white border-b border-gray-200 shadow-sm">

    <div class="flex items-center justify-between h-20 px-8">

        <div>

            <h1 class="text-2xl font-bold text-gray-800">
                @yield('page-title', 'Preventivas')
            </h1>

            <p class="text-sm text-gray-500">
                Sistema de Gestão de Preventivas
            </p>

        </div>

        <div class="flex items-center gap-6">

            <div class="h-10 w-px bg-gray-200"></div>

            <div class="flex items-center gap-4">

                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-lg font-semibold text-white">

                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                </div>

                <div class="text-right">

                    <p class="font-semibold text-gray-800">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ auth()->user()->role->name }}
                    </p>

                </div>

            </div>

            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-red-700 transition"
                >
                    Sair
                </button>

            </form>

        </div>

    </div>

</header>
