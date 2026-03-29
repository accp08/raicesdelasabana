<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PropertySlugGenerator
{
    public function generate(array $data, ?Property $property = null): string
    {
        $manualSlug = trim((string) ($data['slug'] ?? ''));
        $descriptiveBase = $manualSlug !== '' ? $manualSlug : $this->buildBase($data);

        return $this->makeUniqueSlug(
            $this->buildSlug($descriptiveBase, $data, $property),
            $property?->id
        );
    }

    public function buildBase(array $data): string
    {
        $title = $this->sanitizeTitle(
            (string) ($data['titulo'] ?? ''),
            $data['property_type'] ?? null,
            $data['tipo'] ?? null,
            $data['ciudad'] ?? null
        );

        $segments = [
            $data['property_type'] ?? null,
            $this->tipoLabel($data['tipo'] ?? null),
            $title,
            $data['barrio'] ?? null,
        ];

        return $this->normalizeSegments($segments)->implode(' ');
    }

    public function buildSlug(string $descriptiveBase, array $data, ?Property $property = null): string
    {
        $city = trim((string) ($data['ciudad'] ?? $property?->ciudad ?? ''));
        $code = trim((string) ($data['property_code'] ?? $property?->property_code ?? ''));

        return $this->normalizeSegments([
            $descriptiveBase,
            $city,
            $code,
        ])->implode(' ');
    }

    public function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: 'propiedad';
        $slug = $base;
        $counter = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function tipoLabel(?string $tipo): ?string
    {
        return match ($tipo) {
            'venta' => 'en venta',
            'arriendo' => 'en arriendo',
            'mixto' => 'venta y arriendo',
            default => null,
        };
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return Property::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function normalizeSegments(array $segments): Collection
    {
        return collect($segments)
            ->filter(fn ($value) => filled($value))
            ->map(function ($value) {
                $value = trim((string) $value);

                return $value === '' ? null : Str::lower($value);
            })
            ->filter()
            ->unique()
            ->values();
    }

    private function sanitizeTitle(string $title, ?string $propertyType, ?string $tipo, ?string $city): string
    {
        $clean = Str::lower(trim($title));
        $propertyType = Str::lower(trim((string) $propertyType));
        $city = Str::lower(trim((string) $city));

        $patterns = array_filter([
            $propertyType !== '' ? '/^'.preg_quote($propertyType, '/').'\s+/' : null,
            $propertyType !== '' ? '/^'.preg_quote($propertyType, '/').'\s+'.$this->tipoLabelPattern($tipo).'\s+/' : null,
            '/^(venta|arriendo)\s+/' ,
            $propertyType !== '' ? '/^(venta|arriendo)\s+'.preg_quote($propertyType, '/').'\s+/' : null,
        ]);

        foreach ($patterns as $pattern) {
            $clean = preg_replace($pattern, '', $clean) ?? $clean;
        }

        if ($city !== '') {
            $clean = preg_replace('/\s+en\s+'.preg_quote($city, '/').'$/', '', $clean) ?? $clean;
        }

        return trim(preg_replace('/\s+/', ' ', $clean) ?? $clean);
    }

    private function tipoLabelPattern(?string $tipo): string
    {
        return match ($tipo) {
            'venta' => '(en\s+venta|venta)',
            'arriendo' => '(en\s+arriendo|arriendo)',
            'mixto' => '(venta\s+y\s+arriendo|en\s+venta\s+y\s+arriendo)',
            default => '(en\s+venta|en\s+arriendo|venta|arriendo)',
        };
    }
}
