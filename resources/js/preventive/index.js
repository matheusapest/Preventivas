document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById(
        'toggle-preventive-quick-actions'
    );

    const content = document.getElementById(
        'preventive-quick-actions-content'
    );

    const label = document.getElementById(
        'preventive-quick-actions-label'
    );

    const icon = document.getElementById(
        'preventive-quick-actions-icon'
    );

    if (!toggleButton || !content || !label || !icon) {
        return;
    }

    toggleButton.addEventListener('click', () => {
        const isExpanded =
            toggleButton.getAttribute('aria-expanded') === 'true';

        const newState = !isExpanded;

        toggleButton.setAttribute(
            'aria-expanded',
            String(newState)
        );

        content.classList.toggle('hidden', !newState);

        label.textContent = newState
            ? 'Ocultar'
            : 'Exibir ações';

        icon.classList.toggle('rotate-180', newState);
    });
});
