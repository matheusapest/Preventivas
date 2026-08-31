document.addEventListener('DOMContentLoaded', () => {
    const formContainer = document.getElementById(
        'operational-profile-form'
    );

    if (!formContainer) {
        return;
    }

    const mode = formContainer.dataset.mode || 'create';

    const availableCategoriesContainer =
        document.getElementById('available-categories');

    const selectedCategoriesContainer =
        document.getElementById('selected-categories');

    const unitTypeSelect =
        document.getElementById('unit_type_id');

    if (
        !availableCategoriesContainer ||
        !selectedCategoriesContainer
    ) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Dados enviados pelo Blade
    |--------------------------------------------------------------------------
    */

    let categories = [];
    let existingCategories = [];

    try {
        categories = JSON.parse(
            formContainer.dataset.categories || '[]'
        );
    } catch (error) {
        console.error(
            'Erro ao interpretar as categorias:',
            error
        );

        categories = [];
    }

    try {
        existingCategories = JSON.parse(
            formContainer.dataset.existingCategories || '[]'
        );
    } catch (error) {
        console.error(
            'Erro ao interpretar a composição existente:',
            error
        );

        existingCategories = [];
    }

    /*
    |--------------------------------------------------------------------------
    | Tipo de unidade
    |--------------------------------------------------------------------------
    |
    | CREATE:
    |   vem do select.
    |
    | EDIT:
    |   vem do input hidden, pois o tipo é bloqueado.
    |
    */

    function getUnitTypeId() {
        if (unitTypeSelect) {
            return unitTypeSelect.value || '';
        }

        const hiddenUnitType =
            document.querySelector(
                'input[name="unit_type_id"]'
            );

        return hiddenUnitType
            ? hiddenUnitType.value
            : '';
    }

    /*
    |--------------------------------------------------------------------------
    | Categorias atualmente adicionadas
    |--------------------------------------------------------------------------
    */

    function getSelectedCategoryIds() {
        return Array.from(
            selectedCategoriesContainer.querySelectorAll(
                '[data-category-id]'
            )
        ).map(input => String(input.dataset.categoryId));
    }

    /*
    |--------------------------------------------------------------------------
    | Estado vazio
    |--------------------------------------------------------------------------
    */

    function renderEmptyState(
        container,
        title,
        description
    ) {
        container.innerHTML = '';

        const element =
            document.createElement('div');

        element.dataset.emptyMessage = 'true';

        element.className =
            'rounded-lg border border-dashed ' +
            'border-slate-300 bg-slate-50 p-6 text-center';

        element.innerHTML = `
            <p class="text-sm font-medium text-slate-700">
                ${title}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                ${description}
            </p>
        `;

        container.appendChild(element);
    }

    /*
    |--------------------------------------------------------------------------
    | Renderiza categorias disponíveis
    |--------------------------------------------------------------------------
    |
    | Uma categoria pode pertencer a vários tipos de unidade.
    |
    | Exemplo:
    |
    | Impressora PDV
    | unit_type_ids: [1, 2]
    |
    | Neste caso ela será exibida tanto para o tipo 1
    | quanto para o tipo 2.
    |
    */

    function renderAvailableCategories() {
        const unitTypeId = String(
            getUnitTypeId()
        );

        availableCategoriesContainer.innerHTML = '';

        if (!unitTypeId) {
            renderEmptyState(
                availableCategoriesContainer,
                'Selecione um tipo de unidade.',
                'As categorias disponíveis serão exibidas aqui.'
            );

            return;
        }

        const selectedIds =
            getSelectedCategoryIds();

        const availableCategories =
            categories.filter(category => {
                const unitTypeIds =
                    Array.isArray(category.unit_type_ids)
                        ? category.unit_type_ids.map(
                            id => String(id)
                        )
                        : [];

                return (
                    unitTypeIds.includes(unitTypeId) &&
                    !selectedIds.includes(
                        String(category.id)
                    )
                );
            });

        if (availableCategories.length === 0) {
            renderEmptyState(
                availableCategoriesContainer,
                'Nenhuma categoria disponível.',
                'Todas as categorias disponíveis já fazem parte da composição.'
            );

            return;
        }

        availableCategories.forEach(category => {
            const button =
                document.createElement('button');

            button.type = 'button';

            button.className =
                'flex w-full items-center justify-between ' +
                'rounded-lg border border-slate-200 bg-white ' +
                'px-4 py-3 text-left text-sm transition ' +
                'hover:border-blue-400 hover:bg-blue-50';

            button.innerHTML = `
                <span class="font-medium text-slate-700">
                    ${category.name}
                </span>

                <span class="text-xs font-medium text-blue-600">
                    Adicionar
                </span>
            `;

            button.addEventListener(
                'click',
                () => {
                    addCategory(category, 1);
                }
            );

            availableCategoriesContainer.appendChild(
                button
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Adiciona categoria à composição
    |--------------------------------------------------------------------------
    */

    function addCategory(
        category,
        quantity = 1
    ) {
        const categoryId =
            String(category.id);

        const alreadyExists =
            selectedCategoriesContainer.querySelector(
                `[data-category-item="${categoryId}"]`
            );

        if (alreadyExists) {
            return;
        }

        const emptyMessage =
            selectedCategoriesContainer.querySelector(
                '[data-empty-message]'
            );

        if (emptyMessage) {
            emptyMessage.remove();
        }

        const index =
            selectedCategoriesContainer.querySelectorAll(
                '[data-category-item]'
            ).length;

        const item =
            document.createElement('div');

        item.dataset.categoryItem =
            categoryId;

        item.className =
            'grid gap-3 rounded-lg border ' +
            'border-slate-200 bg-white p-4 ' +
            'sm:grid-cols-[1fr_120px_auto] ' +
            'sm:items-center';

        item.innerHTML = `
            <div>
                <p class="text-sm font-medium text-slate-900">
                    ${category.name}
                </p>

                <input
                    type="hidden"
                    data-category-id="${category.id}"
                    name="categories[${index}][category_id]"
                    value="${category.id}"
                >
            </div>

            <div>
                <label
                    for="category-quantity-${category.id}"
                    class="mb-1 block text-xs font-medium text-slate-500"
                >
                    Quantidade
                </label>

                <input
                    type="number"
                    id="category-quantity-${category.id}"
                    name="categories[${index}][quantity]"
                    value="${quantity}"
                    min="1"
                    step="1"
                    required
                    class="w-full rounded-lg border border-slate-300
                           bg-white px-3 py-2 text-center text-sm
                           text-slate-900 focus:border-blue-500
                           focus:ring-blue-500"
                >
            </div>

            <div class="flex justify-end">
                <button
                    type="button"
                    data-remove-category
                    class="rounded-lg px-3 py-2 text-xs
                           font-medium text-red-600
                           transition hover:bg-red-50"
                >
                    Remover
                </button>
            </div>
        `;

        const removeButton =
            item.querySelector(
                '[data-remove-category]'
            );

        removeButton.addEventListener(
            'click',
            () => {
                item.remove();

                rebuildIndexes();

                renderAvailableCategories();

                const remaining =
                    selectedCategoriesContainer.querySelectorAll(
                        '[data-category-item]'
                    );

                if (remaining.length === 0) {
                    renderEmptyState(
                        selectedCategoriesContainer,
                        'Nenhuma categoria adicionada.',
                        'Clique nas categorias acima para montar a composição.'
                    );
                }
            }
        );

        selectedCategoriesContainer.appendChild(
            item
        );

        rebuildIndexes();

        renderAvailableCategories();
    }

    /*
    |--------------------------------------------------------------------------
    | Reorganiza os índices do array enviado ao Laravel
    |--------------------------------------------------------------------------
    */

    function rebuildIndexes() {
        const items =
            selectedCategoriesContainer.querySelectorAll(
                '[data-category-item]'
            );

        items.forEach((item, index) => {
            const categoryInput =
                item.querySelector(
                    'input[data-category-id]'
                );

            const quantityInput =
                item.querySelector(
                    'input[type="number"]'
                );

            if (categoryInput) {
                categoryInput.name =
                    `categories[${index}][category_id]`;
            }

            if (quantityInput) {
                quantityInput.name =
                    `categories[${index}][quantity]`;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Carrega composição existente
    |--------------------------------------------------------------------------
    |
    | Esta parte é executada SOMENTE no EDIT.
    |
    */

    function loadExistingComposition() {
        if (mode !== 'edit') {
            return;
        }

        if (
            !Array.isArray(existingCategories) ||
            existingCategories.length === 0
        ) {
            return;
        }

        existingCategories.forEach(existing => {
            const category =
                categories.find(
                    item =>
                        String(item.id) ===
                        String(existing.category_id)
                );

            if (!category) {
                console.warn(
                    'Categoria da composição não encontrada:',
                    existing.category_id
                );

                return;
            }

            const quantity =
                Number(existing.quantity);

            addCategory(
                category,
                quantity > 0
                    ? quantity
                    : 1
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Mudança do tipo de unidade
    |--------------------------------------------------------------------------
    |
    | Só existe no CREATE.
    |
    | Quando mudar o tipo:
    | - limpa a composição
    | - mostra as categorias compatíveis
    |
    */

    if (unitTypeSelect) {
        unitTypeSelect.addEventListener(
            'change',
            () => {
                selectedCategoriesContainer.innerHTML = '';

                renderEmptyState(
                    selectedCategoriesContainer,
                    'Nenhuma categoria adicionada.',
                    'Clique nas categorias acima para montar a composição.'
                );

                renderAvailableCategories();
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Inicialização
    |--------------------------------------------------------------------------
    */

    if (mode === 'edit') {
        loadExistingComposition();
    }

    renderAvailableCategories();

    /*
    |--------------------------------------------------------------------------
    | Estado inicial da composição
    |--------------------------------------------------------------------------
    */

    if (
        selectedCategoriesContainer.querySelectorAll(
            '[data-category-item]'
        ).length === 0
    ) {
        renderEmptyState(
            selectedCategoriesContainer,
            'Nenhuma categoria adicionada.',
            'Clique nas categorias acima para montar a composição.'
        );
    }
});
