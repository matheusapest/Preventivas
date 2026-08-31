/**
 * Controle de expansão e recolhimento dos módulos da Sidebar
 */
export function initSidebarAccordion() {
    // Seleciona todos os botões de controle de módulo
    const groupToggles = document.querySelectorAll('.sidebar-group-toggle');

    groupToggles.forEach(toggle => {
        toggle.addEventListener('click', (event) => {
            event.preventDefault();

            const group = toggle.closest('.sidebar-group');
            if (!group) return;

            const content = group.querySelector('.sidebar-group-content');
            const chevron = toggle.querySelector('.sidebar-chevron');

            if (!content) return;

            // Verifica se o submenu atual está oculto
            const isHidden = content.classList.contains('hidden');

            if (isHidden) {
                // Abre o módulo clicado
                content.classList.remove('hidden');
                chevron?.classList.add('rotate-180');
                toggle.setAttribute('aria-expanded', 'true');
            } else {
                // Oculta o módulo clicado
                content.classList.add('hidden');
                chevron?.classList.remove('rotate-180');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    });
}

// Inicializa a função assim que o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    initSidebarAccordion();
});
