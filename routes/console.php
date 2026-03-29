<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Property;
use App\Services\ImageOptimizer;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('properties:watermark {--dry-run : Solo muestra cuántas imágenes se procesarían}', function () {
    $optimizer = app(ImageOptimizer::class);
    $paths = [];

    Property::query()->select(['id', 'imagen_principal', 'imagen_principal_thumb', 'galeria', 'galeria_thumbs'])
        ->chunkById(100, function ($properties) use (&$paths) {
            foreach ($properties as $property) {
                foreach ([
                    $property->imagen_principal,
                    $property->imagen_principal_thumb,
                    ...((array) $property->galeria),
                    ...((array) $property->galeria_thumbs),
                ] as $path) {
                    if (is_string($path) && $path !== '') {
                        $paths[] = ltrim($path, '/');
                    }
                }
            }
        });

    $paths = array_values(array_unique($paths));
    $total = count($paths);

    if ($total === 0) {
        $this->warn('No se encontraron imágenes de propiedades para reprocesar.');
        return;
    }

    $this->info("Imágenes encontradas: {$total}");

    if ($this->option('dry-run')) {
        $this->line('Modo dry-run activado. No se realizaron cambios.');
        return;
    }

    $processed = 0;
    $failed = 0;

    $this->withProgressBar($paths, function (string $path) use ($optimizer, &$processed, &$failed) {
        if ($optimizer->applyWatermarkToPublicPath($path)) {
            $processed++;
            return;
        }
        $failed++;
    });

    $this->newLine(2);
    $this->info("Procesadas: {$processed}");
    if ($failed > 0) {
        $this->warn("No procesadas: {$failed}");
    }
})->purpose('Reprocesa imágenes existentes de propiedades y aplica marca de agua con logo.');
