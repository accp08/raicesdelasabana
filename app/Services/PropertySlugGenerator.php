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

        $propertyType = $this->normalizeLabel((string) ($data['property_type'] ?? ''));
        $operation = $this->tipoKeyword($data['tipo'] ?? null);
        $city = $this->normalizeLabel((string) ($data['ciudad'] ?? ''));
        $barrio = $this->normalizeLabel((string) ($data['barrio'] ?? ''));
        $titleContext = $this->compactTitleContext($title, $propertyType, $operation, $city, $barrio);
        $location = $barrio !== '' ? $barrio : $titleContext;

        $segments = [
            $propertyType,
            $operation,
            $location,
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
            $code !== '' ? Str::lower($code) : null,
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

    public function tipoKeyword(?string $tipo): ?string
    {
        return match ($tipo) {
            'venta' => 'venta',
            'arriendo' => 'arriendo',
            'mixto' => 'mixto',
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

    private function normalizeLabel(string $value): string
    {
        $value = Str::lower(trim($value));
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return trim($value);
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

    private function compactTitleContext(
        string $title,
        string $propertyType,
        ?string $operation,
        string $city,
        string $barrio
    ): ?string {
        $title = $this->normalizeLabel($title);

        if ($title === '') {
            return null;
        }

        $stopWords = [
            'de', 'del', 'la', 'las', 'el', 'los', 'y', 'en', 'para', 'por', 'con', 'un', 'una',
            'apartamento', 'casa', 'lote', 'casa lote', 'oficina', 'bodega', 'finca',
            'venta', 'arriendo', 'mixto',
        ];

        $tokens = collect(explode(' ', $title))
            ->map(fn ($token) => trim($token))
            ->filter()
            ->reject(function ($token) use ($stopWords, $propertyType, $operation, $city, $barrio) {
                if (in_array($token, $stopWords, true)) {
                    return true;
                }

                foreach ([$propertyType, $operation ?? '', $city, $barrio] as $blocked) {
                    if ($blocked !== '' && str_contains($blocked, $token)) {
                        return true;
                    }
                }

                return false;
            })
            ->unique()
            ->take(4)
            ->values();

        if ($tokens->isEmpty()) {
            return null;
        }

        return $tokens->implode(' ');
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
