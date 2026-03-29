<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Support\Facades\DB;

class PropiedadesController extends Controller
{
    public function index()
    {
        $query = Property::query()->where('status', 'published');

        if ($cityId = request('city_id')) {
            $query->where('city_id', $cityId);
        }

        if ($type = request('tipo')) {
            if ($type === 'venta') {
                $query->where('for_sale', true);
            }
            if ($type === 'arriendo') {
                $query->where('for_rent', true);
            }
        }

        if ($types = request('property_type')) {
            $types = array_filter((array) $types);
            if (count($types) > 0) {
                $query->whereIn('property_type', $types);
            }
        }

        $propiedades = $query->with('city')
            ->orderByDesc('is_featured')
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $ciudades = \App\Models\City::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if (request()->ajax()) {
            return response()->json([
                'html' => view('propiedades.partials.cards', compact('propiedades'))->render(),
                'next_page_url' => $propiedades->nextPageUrl(),
            ]);
        }

        return view('propiedades.index', [
            'propiedades' => $propiedades,
            'ciudades' => $ciudades,
        ]);
    }

    public function show(string $slug)
    {
        $property = Property::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        Property::where('id', $property->id)->update([
            'views' => DB::raw('views + 1'),
            'last_viewed_at' => now(),
        ]);

        return view('interna', compact('property'));
    }
}
