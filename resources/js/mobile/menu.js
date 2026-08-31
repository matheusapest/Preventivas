document.addEventListener('DOMContentLoaded', () => {

    const sidebar = document.getElementById('sidebar');
    const button = document.getElementById('mobile-menu-button');
    const backdrop = document.getElementById('sidebar-backdrop');

    if (!sidebar || !button) {
        console.warn('MENU.JS: sidebar ou mobile-menu-button não encontrado.');
        return;
    }

    function openMenu() {
        // Exibe a sidebar deslizando da esquerda no mobile/tablet
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');

        // Exibe o backdrop escurecido
        if (backdrop) {
            backdrop.classList.remove('hidden');
        }

        // Trava o scroll da página enquanto a sidebar estiver aberta no mobile
        document.body.classList.add('overflow-hidden');

        button.setAttribute('aria-expanded', 'true');
        button.setAttribute('aria-label', 'Fechar menu');
    }

    function closeMenu() {
        // Oculta a sidebar para fora da tela no mobile/tablet
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');

        // Esconde o backdrop
        if (backdrop) {
            backdrop.classList.add('hidden');
        }

        // Libera o scroll da página
        document.body.classList.remove('overflow-hidden');

        button.setAttribute('aria-expanded', 'false');
        button.setAttribute('aria-label', 'Abrir menu');
    }

    function toggleMenu() {
        const isOpen = button.getAttribute('aria-expanded') === 'true';
        if (isOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    }

    // Evento de clique no botão hambúrguer
    button.addEventListener('click', (event) => {
        event.preventDefault();
        toggleMenu();
    });

    // Clicar no backdrop (fundo escuro) fecha o menu
    if (backdrop) {
        backdrop.addEventListener('click', closeMenu);
    }

    // Tecla ESC fecha o menu
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMenu();
        }
    });

    // Ao redimensionar para desktop (>= 1024px / lg), reseta o estado do mobile
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            closeMenu();
        }
    });

    // Estado inicial: garante que o menu inicia fechado
    closeMenu();
});
