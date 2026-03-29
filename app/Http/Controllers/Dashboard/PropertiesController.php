<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyStoreRequest;
use App\Http\Requests\PropertyUpdateRequest;
use App\Models\Property;
use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertiesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Property::class, 'property');
    }

    public function index()
    {
        $query = Property::query();

        if ($search = request('search')) {
            $query->where('titulo', 'like', "%{$search}%")
                ->orWhere('ciudad', 'like', "%{$search}%");
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($city = request('ciudad')) {
            $query->where('ciudad', $city);
        }

        if ($type = request('tipo')) {
            $query->where('tipo', $type);
        }

        $properties = $query->with('city')->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('dashboard.properties.index', compact('properties'));
    }

    public function create()
    {
        $cities = \App\Models\City::where('is_active', true)->orderBy('name')->get();

        return view('dashboard.properties.create', compact('cities'));
    }

    public function store(PropertyStoreRequest $request)
    {
        $data = $request->validated();
        $data['property_code'] = $this->generateUniqueCode();
        $data['slug'] = $this->makeUniqueSlug(
            Property::class,
            $data['slug'] ?? $data['titulo'],
            null,
            $data['property_code']
        );
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;
        $data['tipo'] = $this->resolveTipo($data['for_sale'] ?? false, $data['for_rent'] ?? false);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['administracion_incluida'] = $request->boolean('for_rent')
            ? $request->boolean('administracion_incluida')
            : null;
        $data['ciudad'] = \App\Models\City::find($data['city_id'])->name ?? $data['ciudad'] ?? '';

        $optimizer = app(ImageOptimizer::class);

        if ($request->hasFile('imagen_principal')) {
            $stored = $optimizer->storeWithThumbnail($request->file('imagen_principal'), 'properties', 1600, 600, 82, true);
            $data['imagen_principal'] = $stored['image'];
            $data['imagen_principal_thumb'] = $stored['thumb'];
        }

        if ($request->hasFile('galeria')) {
            $gallery = [];
            $galleryThumbs = [];
            foreach ($request->file('galeria') as $image) {
                $stored = $optimizer->storeWithThumbnail($image, 'properties', 1600, 600, 82, true);
                $gallery[] = $stored['image'];
                if ($stored['thumb']) {
                    $galleryThumbs[] = $stored['thumb'];
                }
            }
            $data['galeria'] = $gallery;
            $data['galeria_thumbs'] = $galleryThumbs;
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($data['status'] !== 'published') {
            $data['published_at'] = null;
        }

        Property::create($data);

        return redirect()->route('dashboard.properties.index')
            ->with('status', 'Propiedad creada correctamente.');
    }

    public function edit(Property $property)
    {
        $cities = \App\Models\City::where('is_active', true)->orderBy('name')->get();

        return view('dashboard.properties.edit', compact('property', 'cities'));
    }

    public function update(PropertyUpdateRequest $request, Property $property)
    {
        $data = $request->validated();
        $propertyCode = $property->property_code;
        if (empty($propertyCode)) {
            $propertyCode = $this->generateUniqueCode();
            $data['property_code'] = $propertyCode;
        }
        $data['slug'] = $this->makeUniqueSlug(
            Property::class,
            $data['slug'] ?? $data['titulo'],
            $property->id,
            $propertyCode
        );
        $data['updated_by'] = $request->user()->id;
        $data['tipo'] = $this->resolveTipo($data['for_sale'] ?? false, $data['for_rent'] ?? false);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['administracion_incluida'] = $request->boolean('for_rent')
            ? $request->boolean('administracion_incluida')
            : null;
        $data['ciudad'] = \App\Models\City::find($data['city_id'])->name ?? $data['ciudad'] ?? $property->ciudad;

        $optimizer = app(ImageOptimizer::class);

        if ($request->hasFile('imagen_principal')) {
            if ($property->imagen_principal) {
                Storage::disk('public')->delete($property->imagen_principal);
            }
            if ($property->imagen_principal_thumb) {
                Storage::disk('public')->delete($property->imagen_principal_thumb);
            }
            $stored = $optimizer->storeWithThumbnail($request->file('imagen_principal'), 'properties', 1600, 600, 82, true);
            $data['imagen_principal'] = $stored['image'];
            $data['imagen_principal_thumb'] = $stored['thumb'];
        }

        if (!empty($data['remove_main_image'])) {
            if ($property->imagen_principal) {
                Storage::disk('public')->delete($property->imagen_principal);
            }
            if ($property->imagen_principal_thumb) {
                Storage::disk('public')->delete($property->imagen_principal_thumb);
            }
            $data['imagen_principal'] = null;
            $data['imagen_principal_thumb'] = null;
        }

        if ($request->hasFile('galeria')) {
            $gallery = $property->galeria ?? [];
            $galleryThumbs = $property->galeria_thumbs ?? [];
            foreach ($request->file('galeria') as $image) {
                $stored = $optimizer->storeWithThumbnail($image, 'properties', 1600, 600, 82, true);
                $gallery[] = $stored['image'];
                if ($stored['thumb']) {
                    $galleryThumbs[] = $stored['thumb'];
                }
            }
            $data['galeria'] = $gallery;
            $data['galeria_thumbs'] = $galleryThumbs;
        }

        if (!empty($data['remove_gallery']) && is_array($property->galeria)) {
            $remove = array_flip($data['remove_gallery']);
            $remaining = [];
            $remainingThumbs = [];

            foreach ($property->galeria as $index => $path) {
                if (isset($remove[$path])) {
                    Storage::disk('public')->delete($path);
                    if (!empty($property->galeria_thumbs[$index])) {
                        Storage::disk('public')->delete($property->galeria_thumbs[$index]);
                    }
                    continue;
                }
                $remaining[] = $path;
                if (!empty($property->galeria_thumbs[$index])) {
                    $remainingThumbs[] = $property->galeria_thumbs[$index];
                }
            }

            $data['galeria'] = $remaining;
            $data['galeria_thumbs'] = $remainingThumbs;
        }

        if (!empty($data['clear_gallery'])) {
            if ($property->galeria) {
                foreach ($property->galeria as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            if ($property->galeria_thumbs) {
                foreach ($property->galeria_thumbs as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            $data['galeria'] = [];
            $data['galeria_thumbs'] = [];
        }

        if (!empty($data['gallery_order']) && empty($data['clear_gallery'])) {
            $order = array_filter(explode('|', $data['gallery_order']));
            $currentGallery = $data['galeria'] ?? $property->galeria ?? [];
            $currentThumbs = $data['galeria_thumbs'] ?? $property->galeria_thumbs ?? [];
            if (!empty($currentGallery)) {
                $thumbMap = [];
                foreach ($currentGallery as $index => $path) {
                    $thumbMap[$path] = $currentThumbs[$index] ?? null;
                }

                $ordered = [];
                $orderedThumbs = [];
                $used = [];

                foreach ($order as $path) {
                    if (array_key_exists($path, $thumbMap)) {
                        $ordered[] = $path;
                        $orderedThumbs[] = $thumbMap[$path];
                        $used[$path] = true;
                    }
                }

                foreach ($currentGallery as $path) {
                    if (isset($used[$path])) {
                        continue;
                    }
                    $ordered[] = $path;
                    $orderedThumbs[] = $thumbMap[$path] ?? null;
                }

                $data['galeria'] = $ordered;
                $data['galeria_thumbs'] = $orderedThumbs;
            }
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($data['status'] !== 'published') {
            $data['published_at'] = null;
        }

        $property->update($data);

        return redirect()->route('dashboard.properties.index')
            ->with('status', 'Propiedad actualizada.');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('dashboard.properties.index')
            ->with('status', 'Propiedad eliminada.');
    }

    private function makeUniqueSlug(
        string $modelClass,
        string $value,
        ?int $ignoreId = null,
        ?string $code = null
    ): string
    {
        $base = Str::slug($value);
        if (!empty($code)) {
            $slug = $base.'-'.Str::lower($code);
            if (!$this->slugExists($modelClass, $slug, $ignoreId)) {
                return $slug;
            }
        }

        $slug = $base;
        $counter = 1;

        while ($this->slugExists($modelClass, $slug, $ignoreId)) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $modelClass, string $slug, ?int $ignoreId = null): bool
    {
        $query = method_exists($modelClass, 'withTrashed')
            ? $modelClass::withTrashed()
            : $modelClass::query();

        return $query->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

    private function resolveTipo(bool $forSale, bool $forRent): string
    {
        if ($forSale && $forRent) {
            return 'mixto';
        }

        if ($forSale) {
            return 'venta';
        }

        return 'arriendo';
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (Property::where('property_code', $code)->exists());

        return $code;
    }
}
