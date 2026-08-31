<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class PreventiveActivityPhotoService
{
    public function __construct(
        private readonly ImageManager $imageManager,
    ) {}

    /**
     * Processa e armazena uma foto de atividade preventiva.
     *
     * A imagem recebe uma marca d'água com a data e hora
     * do processamento antes de ser armazenada.
     *
     * @return array{
     *     path: string,
     *     mime_type: string,
     *     size: int,
     *     captured_at: CarbonImmutable
     * }
     */
    public function store(UploadedFile $file): array
    {
        /*
         * O horário utilizado aqui será o mesmo:
         * - registrado em captured_at;
         * - utilizado na marca d'água.
         */
        $capturedAt = CarbonImmutable::now();

        /*
         * Carrega a imagem recebida pelo formulário.
         */
        $image = $this->imageManager
            ->decodeSplFileInfo($file);

        /*
         * Adiciona a marca d'água.
         */
        $this->addWatermark(
            $image,
            $capturedAt->format('d/m/Y H:i:s')
        );

        /*
         * Gera um nome único para a imagem processada.
         */
        $filename = uniqid(
            'preventive_',
            true
        ) . '.jpg';

        $path = 'preventive-photos/' . $filename;

        /*
         * Obtém o caminho físico dentro do Storage privado.
         */
        $fullPath = Storage::disk('local')
            ->path($path);

        /*
         * Salva sempre como JPEG.
         */
        $image->save($fullPath);

        /*
         * Obtém o tamanho real do arquivo processado,
         * e não o tamanho do arquivo original enviado.
         */
        $size = Storage::disk('local')
            ->size($path);

        return [
            'path' => $path,
            'mime_type' => 'image/jpeg',
            'size' => $size,
            'captured_at' => $capturedAt,
        ];
    }

    /**
     * Adiciona a marca d'água à imagem.
     */
    private function addWatermark(
        $image,
        string $capturedAt
    ): void {
        /**
         * Fonte TTF disponível dentro do projeto.
         */
        $fontPath = base_path(
            'vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf'
        );

        /**
         * Tamanho proporcional à resolução da imagem.
         *
         * Para uma imagem de 3024 px:
         *
         * 3024 * 0.035 = aproximadamente 106 px.
         *
         * Mantemos limites para evitar tamanhos extremos.
         */
        $fontSize = min(
            120,
            max(
                60,
                (int) ($image->width() * 0.035)
            )
        );

        /**
         * Margem proporcional à resolução.
         */
        $margin = max(
            30,
            (int) ($image->width() * 0.015)
        );

        $x = $margin;

        $y = (int) (
            $image->height()
            - ($fontSize * 2.4)
            - $margin
        );

        $image->text(
            "PREVENTIVAS\n{$capturedAt}",
            $x,
            $y,
            function ($font) use (
                $fontPath,
                $fontSize
            ): void {
                $font->filename($fontPath);
                $font->size($fontSize);
                $font->color('#ffffff');
                $font->stroke('#000000', 2);
            }
        );
    }
}
