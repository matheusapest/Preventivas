{{-- resources/views/test/camera.blade.php --}}

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Teste de Câmera</title>

    @vite(['resources/css/app.css'])

</head>

<body class="min-h-screen bg-slate-100">

    <main class="mx-auto max-w-xl px-4 py-6">

        {{-- ============================================================
             CABEÇALHO
        ============================================================= --}}

        <div class="mb-5">

            <h1 class="text-xl font-semibold text-slate-900">
                Teste de Câmera
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Laboratório para testar a captura de fotos pelo dispositivo.
            </p>

        </div>


        {{-- ============================================================
             STATUS
        ============================================================= --}}

        <div
            id="camera-status"
            class="mb-4 rounded-lg border border-slate-200 bg-white p-4 text-sm text-slate-700"
        >
            Aguardando inicialização da câmera...
        </div>


        {{-- ============================================================
             CÂMERA
        ============================================================= --}}

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-4 py-3">

                <h2 class="text-sm font-semibold text-slate-800">
                    Câmera
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Utilize a câmera traseira do dispositivo.
                </p>

            </div>


            {{-- VISUALIZAÇÃO DA CÂMERA --}}

            <div class="relative aspect-[4/3] w-full bg-black">

                <video
                    id="camera-video"
                    class="h-full w-full object-cover"
                    autoplay
                    playsinline
                    muted
                ></video>

            </div>


            {{-- CONTROLES --}}

            <div class="space-y-3 p-4">

                <button
                    type="button"
                    id="start-camera"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 active:scale-[0.98]"
                >
                    Abrir câmera
                </button>


                <button
                    type="button"
                    id="take-photo"
                    class="hidden inline-flex w-full items-center justify-center rounded-lg bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 active:scale-[0.98]"
                >
                    Tirar foto
                </button>


                <button
                    type="button"
                    id="retake-photo"
                    class="hidden inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]"
                >
                    Tirar outra foto
                </button>

            </div>

        </section>


        {{-- ============================================================
             FOTO CAPTURADA
        ============================================================= --}}

        <section
            id="photo-result"
            class="mt-5 hidden overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm"
        >

            <div class="border-b border-slate-200 px-4 py-3">

                <h2 class="text-sm font-semibold text-slate-800">
                    Foto capturada
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Esta imagem existe apenas no navegador.
                </p>

            </div>


            <div class="bg-black">

                <img
                    id="captured-photo"
                    src=""
                    alt="Foto capturada"
                    class="max-h-[70vh] w-full object-contain"
                >

            </div>


            <div class="space-y-2 p-4">

                <div class="rounded-lg bg-slate-50 p-3">

                    <span class="block text-[10px] font-semibold uppercase tracking-wider text-slate-400">
                        Capturada em
                    </span>

                    <span
                        id="capture-date"
                        class="mt-1 block text-sm font-medium text-slate-700"
                    >
                        —
                    </span>

                </div>


                <button
                    type="button"
                    id="retake-photo-result"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 active:scale-[0.98]"
                >
                    Tirar outra foto
                </button>

            </div>

        </section>


        {{-- ============================================================
             INFORMAÇÕES TÉCNICAS
        ============================================================= --}}

        <section class="mt-5 rounded-xl border border-slate-200 bg-white p-4">

            <h2 class="text-sm font-semibold text-slate-800">
                Informações do dispositivo
            </h2>

            <dl class="mt-3 space-y-2 text-xs">

                <div class="flex justify-between gap-4">

                    <dt class="text-slate-500">
                        Câmera
                    </dt>

                    <dd
                        id="camera-support"
                        class="text-right font-medium text-slate-700"
                    >
                        Verificando...
                    </dd>

                </div>


                <div class="flex justify-between gap-4">

                    <dt class="text-slate-500">
                        HTTPS
                    </dt>

                    <dd
                        id="secure-context"
                        class="text-right font-medium text-slate-700"
                    >
                        Verificando...
                    </dd>

                </div>


                <div class="flex justify-between gap-4">

                    <dt class="text-slate-500">
                        Dispositivo
                    </dt>

                    <dd
                        id="device-type"
                        class="max-w-[65%] break-words text-right font-medium text-slate-700"
                    >
                        Verificando...
                    </dd>

                </div>

            </dl>

        </section>


        <canvas
            id="camera-canvas"
            class="hidden"
        ></canvas>

    </main>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const video = document.getElementById('camera-video');

            const canvas = document.getElementById('camera-canvas');

            const startButton = document.getElementById('start-camera');

            const takeButton = document.getElementById('take-photo');

            const retakeButton = document.getElementById('retake-photo');

            const retakeResultButton = document.getElementById('retake-photo-result');

            const status = document.getElementById('camera-status');

            const photoResult = document.getElementById('photo-result');

            const capturedPhoto = document.getElementById('captured-photo');

            const captureDate = document.getElementById('capture-date');

            const cameraSupport = document.getElementById('camera-support');

            const secureContext = document.getElementById('secure-context');

            const deviceType = document.getElementById('device-type');


            let mediaStream = null;


            /*
             * ---------------------------------------------------------
             * INFORMAÇÕES DO AMBIENTE
             * ---------------------------------------------------------
             */

            const hasCameraApi =
                !!navigator.mediaDevices &&
                !!navigator.mediaDevices.getUserMedia;

            cameraSupport.textContent =
                hasCameraApi
                    ? 'Disponível'
                    : 'Não disponível';


            secureContext.textContent =
                window.isSecureContext
                    ? 'Seguro'
                    : 'Não seguro';


            const userAgent =
                navigator.userAgent.toLowerCase();


            const isMobile =
                /android|iphone|ipad|ipod|mobile/i.test(userAgent);


            deviceType.textContent =
                isMobile
                    ? 'Dispositivo móvel'
                    : 'Desktop';


            /*
             * ---------------------------------------------------------
             * ATUALIZA STATUS
             * ---------------------------------------------------------
             */

            function setStatus(message, type = 'normal') {

                status.textContent = message;

                status.className =
                    'mb-4 rounded-lg border p-4 text-sm';

                if (type === 'error') {

                    status.classList.add(
                        'border-red-200',
                        'bg-red-50',
                        'text-red-800'
                    );

                    return;
                }

                if (type === 'success') {

                    status.classList.add(
                        'border-emerald-200',
                        'bg-emerald-50',
                        'text-emerald-800'
                    );

                    return;
                }

                status.classList.add(
                    'border-slate-200',
                    'bg-white',
                    'text-slate-700'
                );

            }


            /*
             * ---------------------------------------------------------
             * ABRIR CÂMERA
             * ---------------------------------------------------------
             */

            async function startCamera() {

                if (!hasCameraApi) {

                    setStatus(
                        'A API de câmera não está disponível neste navegador.',
                        'error'
                    );

                    return;
                }


                if (!window.isSecureContext) {

                    setStatus(
                        'A câmera exige HTTPS ou um contexto seguro.',
                        'error'
                    );

                    return;
                }


                try {

                    setStatus(
                        'Solicitando acesso à câmera...'
                    );


                    if (mediaStream) {

                        mediaStream
                            .getTracks()
                            .forEach(function (track) {

                                track.stop();

                            });

                    }


                    mediaStream =
                        await navigator.mediaDevices.getUserMedia({

                            video: {

                                facingMode: {
                                    ideal: 'environment'
                                }

                            },

                            audio: false

                        });


                    video.srcObject =
                        mediaStream;


                    await video.play();


                    startButton.classList.add('hidden');

                    takeButton.classList.remove('hidden');

                    photoResult.classList.add('hidden');

                    setStatus(
                        'Câmera iniciada. Posicione o item e tire a foto.',
                        'success'
                    );

                } catch (error) {

                    console.error(error);

                    let message =
                        'Não foi possível acessar a câmera.';


                    if (error.name === 'NotAllowedError') {

                        message =
                            'O acesso à câmera foi negado. Verifique a permissão do navegador.';

                    } else if (error.name === 'NotFoundError') {

                        message =
                            'Nenhuma câmera foi encontrada no dispositivo.';

                    } else if (error.name === 'NotReadableError') {

                        message =
                            'A câmera está sendo utilizada por outro aplicativo.';

                    } else if (error.name === 'SecurityError') {

                        message =
                            'O navegador bloqueou o acesso à câmera por motivos de segurança.';

                    }


                    setStatus(
                        message,
                        'error'
                    );

                }

            }


            /*
             * ---------------------------------------------------------
             * CAPTURAR FOTO
             * ---------------------------------------------------------
             */

            function takePhoto() {

                if (!mediaStream) {

                    setStatus(
                        'A câmera ainda não foi iniciada.',
                        'error'
                    );

                    return;
                }


                const width =
                    video.videoWidth;

                const height =
                    video.videoHeight;


                if (!width || !height) {

                    setStatus(
                        'A câmera ainda não está pronta para captura.',
                        'error'
                    );

                    return;
                }


                canvas.width =
                    width;

                canvas.height =
                    height;


                const context =
                    canvas.getContext('2d');


                context.drawImage(
                    video,
                    0,
                    0,
                    width,
                    height
                );


                const imageData =
                    canvas.toDataURL(
                        'image/jpeg',
                        0.92
                    );


                capturedPhoto.src =
                    imageData;


                const now =
                    new Date();


                captureDate.textContent =
                    now.toLocaleString(
                        'pt-BR'
                    );


                photoResult.classList.remove(
                    'hidden'
                );


                takeButton.classList.add(
                    'hidden'
                );


                retakeButton.classList.remove(
                    'hidden'
                );


                setStatus(
                    'Foto capturada com sucesso.',
                    'success'
                );


                /*
                 * Não encerramos a câmera aqui.
                 *
                 * Isso permite tirar outra foto rapidamente.
                 */

            }


            /*
             * ---------------------------------------------------------
             * NOVA FOTO
             * ---------------------------------------------------------
             */

            function retakePhoto() {

                photoResult.classList.add(
                    'hidden'
                );

                retakeButton.classList.add(
                    'hidden'
                );

                takeButton.classList.remove(
                    'hidden'
                );

                setStatus(
                    'Câmera pronta. Posicione novamente o item.'
                );

            }


            /*
             * ---------------------------------------------------------
             * EVENTOS
             * ---------------------------------------------------------
             */

            startButton.addEventListener(
                'click',
                startCamera
            );


            takeButton.addEventListener(
                'click',
                takePhoto
            );


            retakeButton.addEventListener(
                'click',
                retakePhoto
            );


            retakeResultButton.addEventListener(
                'click',
                retakePhoto
            );


            /*
             * ---------------------------------------------------------
             * ENCERRAR CÂMERA
             * ---------------------------------------------------------
             */

            window.addEventListener(
                'beforeunload',
                function () {

                    if (!mediaStream) {
                        return;
                    }

                    mediaStream
                        .getTracks()
                        .forEach(function (track) {

                            track.stop();

                        });

                }
            );


            /*
             * ---------------------------------------------------------
             * STATUS INICIAL
             * ---------------------------------------------------------
             */

            if (!hasCameraApi) {

                setStatus(
                    'Este navegador não disponibiliza a API necessária para acessar a câmera.',
                    'error'
                );

                return;
            }


            if (!window.isSecureContext) {

                setStatus(
                    'A página não está em um contexto seguro. Para testar a câmera no iPhone/Android, utilize HTTPS.',
                    'error'
                );

                return;
            }


            setStatus(
                'Ambiente preparado. Toque em "Abrir câmera" para começar.'
            );

        });

    </script>

</body>

</html>
