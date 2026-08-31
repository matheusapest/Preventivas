<x-cards.card>

    <div class="border-b border-slate-200 px-6 py-4">

        <h2 class="text-lg font-semibold text-slate-800">
            Dados do Usuário
        </h2>

    </div>

    <div class="space-y-6 p-6">

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <x-forms.input
                name="name"
                label="Nome"
                :value="$user->name ?? null"
                required
            />

            <x-forms.input
                name="email"
                label="E-mail"
                type="email"
                :value="$user->email ?? null"
                required
            />

        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            @if ($mode === 'create')

                <x-forms.select
                    name="role_id"
                    label="Perfil"
                    :options="$roles"
                    required
                />

            @elseif ($mode === 'edit')

                @can('updateRole', $user)

                    <x-forms.select
                        name="role_id"
                        label="Perfil"
                        :options="$roles"
                        :value="$user->role_id"
                        required
                    />

                @endcan

            @endif

            <x-forms.input
                name="password"
                label="Senha"
                type="password"
                :required="$mode === 'create'"
                :help="$mode === 'edit'
                    ? 'Deixe em branco para manter a senha atual.'
                    : null"
            />

        </div>

        @if ($mode === 'create')

            <x-forms.checkbox
                name="active"
                label="Usuário ativo"
                :checked="true"
            />

        @elseif ($mode === 'edit')

            @can('toggleActive', $user)

                <x-forms.checkbox
                    name="active"
                    label="Usuário ativo"
                    :checked="$user->active"
                />

            @endcan

        @endif

    </div>

</x-cards.card>
