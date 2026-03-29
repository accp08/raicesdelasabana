<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use App\Models\Property;
use App\Services\ImageOptimizer;
use App\Services\PropertySlugGenerator;

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

Artisan::command('properties:sync-slugs {--dry-run : Solo muestra los cambios sin guardar} {--force : Regenera todos los slugs, incluso si ya parecen correctos}', function (PropertySlugGenerator $slugGenerator) {
    $dryRun = (bool) $this->option('dry-run');
    $force = (bool) $this->option('force');
    $changes = [];

    Property::withTrashed()
        ->orderBy('id')
        ->chunkById(100, function ($properties) use (&$changes, $slugGenerator, $force, $dryRun) {
            foreach ($properties as $property) {
                $currentSlug = trim((string) $property->slug);
                $generatedSlug = $slugGenerator->generate([
                    'slug' => '',
                    'titulo' => $property->titulo,
                    'property_type' => $property->property_type,
                    'tipo' => $property->tipo,
                    'barrio' => $property->barrio,
                    'ciudad' => $property->ciudad,
                    'property_code' => $property->property_code,
                ], $property);

                $seemsAligned = $property->property_code
                    && $property->ciudad
                    && str_contains($currentSlug, Str::slug($property->ciudad))
                    && str_contains($currentSlug, Str::lower($property->property_code));

                if (!$force && $seemsAligned) {
                    continue;
                }

                if ($generatedSlug === $currentSlug) {
                    continue;
                }

                $changes[] = [
                    'id' => $property->id,
                    'from' => $currentSlug,
                    'to' => $generatedSlug,
                ];

                if (!$dryRun) {
                    $property->forceFill(['slug' => $generatedSlug])->saveQuietly();
                }
            }
        });

    if (empty($changes)) {
        $this->info('No se encontraron slugs para actualizar.');
        return;
    }

    $this->info('Slugs detectados para actualizar: '.count($changes));

    foreach (array_slice($changes, 0, 20) as $change) {
        $this->line("#{$change['id']}: {$change['from']} => {$change['to']}");
    }

    if (count($changes) > 20) {
        $this->line('...');
    }

    if ($dryRun) {
        $this->warn('Modo dry-run: no se guardaron cambios.');
        return;
    }

    $this->info('Slugs actualizados correctamente.');
})->purpose('Recalcula los slugs de propiedades existentes para incluir formato descriptivo, ciudad y código.');
