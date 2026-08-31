document.addEventListener('DOMContentLoaded', () => {

    const button =
        document.getElementById('toggle-quick-actions');

    const content =
        document.getElementById('quick-actions-content');

    const icon =
        document.getElementById('quick-actions-icon');

    const label =
        document.getElementById('quick-actions-label');


    /*
     * A página não possui o componente.
     *
     * Evita qualquer erro caso este JS
     * seja carregado em outra página.
     */
    if (
        !button ||
        !content ||
        !icon ||
        !label
    ) {
        return;
    }


    /*
     * Abre e fecha o painel de ações rápidas.
     */
    button.addEventListener('click', () => {

        const isOpen =
            button.getAttribute('aria-expanded') === 'true';


        /*
         * Atualiza o estado de acessibilidade.
         */
        button.setAttribute(
            'aria-expanded',
            String(!isOpen)
        );


        /*
         * Exibe ou oculta as ações.
         */
        content.classList.toggle(
            'hidden',
            isOpen
        );


        /*
         * Rotaciona a seta.
         */
        icon.classList.toggle(
            'rotate-180',
            !isOpen
        );


        /*
         * Atualiza o texto auxiliar.
         */
        label.textContent =
            isOpen
                ? 'Exibir ações'
                : 'Ocultar';

    });

});
