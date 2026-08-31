document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | MODAL GENÉRICO DE EVIDÊNCIA FOTOGRÁFICA
    |--------------------------------------------------------------------------
    |
    | Qualquer página que possua:
    |
    |   [data-photo-modal-open]
    |
    | poderá utilizar este modal.
    |
    | O botão deve possuir:
    |
    |   data-photo-url="URL_DA_IMAGEM"
    |
    */

    const photoModal =
        document.getElementById('photo-modal');

    const photoModalImage =
        document.getElementById('photo-modal-image');

    const photoOpenButtons =
        document.querySelectorAll(
            '[data-photo-modal-open]'
        );

    if (
        !photoModal ||
        !photoModalImage ||
        photoOpenButtons.length === 0
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | ELEMENTOS DO MODAL
    |--------------------------------------------------------------------------
    */

    const photoModalContent =
        photoModal.querySelector(
            '[data-photo-modal-content]'
        );

    const photoModalBackdrop =
        photoModal.querySelector(
            '[data-photo-modal-backdrop]'
        );

    const photoModalClose =
        photoModal.querySelector(
            '[data-photo-modal-close]'
        );


    /*
    |--------------------------------------------------------------------------
    | ABRIR
    |--------------------------------------------------------------------------
    */

    function openPhotoModal(
        url,
        alt = 'Evidência fotográfica'
    ) {

        if (!url) {
            return;
        }


        photoModalImage.src = url;

        photoModalImage.alt = alt;


        /*
        |--------------------------------------------------------------------------
        | ESTADO INICIAL
        |--------------------------------------------------------------------------
        */

        photoModal.classList.remove('hidden');

        photoModal.classList.add('flex');

        photoModal.classList.remove('opacity-100');

        photoModal.classList.add('opacity-0');


        if (photoModalContent) {

            photoModalContent.classList.remove(
                'scale-100'
            );

            photoModalContent.classList.add(
                'scale-95'
            );
        }


        photoModal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.classList.add(
            'overflow-hidden'
        );


        /*
        |--------------------------------------------------------------------------
        | ANIMAÇÃO
        |--------------------------------------------------------------------------
        */

        requestAnimationFrame(function () {

            photoModal.classList.remove(
                'opacity-0'
            );

            photoModal.classList.add(
                'opacity-100'
            );


            if (photoModalContent) {

                photoModalContent.classList.remove(
                    'scale-95'
                );

                photoModalContent.classList.add(
                    'scale-100'
                );
            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | FECHAR
    |--------------------------------------------------------------------------
    */

    function closePhotoModal() {

        photoModal.classList.remove(
            'opacity-100'
        );

        photoModal.classList.add(
            'opacity-0'
        );


        if (photoModalContent) {

            photoModalContent.classList.remove(
                'scale-100'
            );

            photoModalContent.classList.add(
                'scale-95'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | AGUARDA A ANIMAÇÃO
        |--------------------------------------------------------------------------
        */

        setTimeout(function () {

            photoModal.classList.add(
                'hidden'
            );

            photoModal.classList.remove(
                'flex'
            );

            photoModal.setAttribute(
                'aria-hidden',
                'true'
            );


            /*
            |--------------------------------------------------------------------------
            | LIMPA A IMAGEM
            |--------------------------------------------------------------------------
            */

            photoModalImage.removeAttribute(
                'src'
            );

            photoModalImage.alt =
                'Evidência fotográfica';


            document.body.classList.remove(
                'overflow-hidden'
            );

        }, 200);

    }


    /*
    |--------------------------------------------------------------------------
    | BOTÕES DAS FOTOS
    |--------------------------------------------------------------------------
    */

    photoOpenButtons.forEach(function (button) {

        button.addEventListener(
            'click',
            function () {

                const photoUrl =
                    button.dataset.photoUrl || '';

                const image =
                    button.querySelector('img');

                const alt =
                    image?.alt ||
                    'Evidência fotográfica';


                openPhotoModal(
                    photoUrl,
                    alt
                );

            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | BOTÃO FECHAR
    |--------------------------------------------------------------------------
    */

    if (photoModalClose) {

        photoModalClose.addEventListener(
            'click',
            closePhotoModal
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BACKDROP
    |--------------------------------------------------------------------------
    */

    if (photoModalBackdrop) {

        photoModalBackdrop.addEventListener(
            'click',
            closePhotoModal
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                !photoModal.classList.contains('hidden')
            ) {

                closePhotoModal();

            }

        }
    );

});
