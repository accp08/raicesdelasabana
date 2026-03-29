<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Support\Facades\DB;

class PropiedadesController extends Controller
{
    public function index()
    {
        $query = Property::query()->where('status', 'published');
        $seed = (int) request('seed', random_int(1000, 999999));

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

        $driver = DB::connection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $query->orderByRaw('RAND(?)', [$seed]);
        } elseif ($driver === 'pgsql') {
            $query->orderByRaw("md5(id::text || ?)", [(string) $seed]);
        } else {
            $query->orderByRaw('abs((id * 1103515245 + ?) % 2147483647)', [$seed]);
        }

        $propiedades = $query->with('city')
            ->paginate(12)
            ->appends(array_merge(request()->query(), ['seed' => $seed]));

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
            'randomSeed' => $seed,
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
