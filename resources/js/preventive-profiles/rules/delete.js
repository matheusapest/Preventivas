document.addEventListener('DOMContentLoaded', () => {
    const deleteForms = document.querySelectorAll(
        '[data-specific-rule-delete]'
    );

    deleteForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            const confirmed = window.confirm(
                'Tem certeza que deseja excluir esta regra específica?'
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});
