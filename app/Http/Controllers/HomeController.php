<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $propiedades = Property::query()
            ->where('status', 'published')
            ->where('is_featured', true)
            ->with('city')
            ->inRandomOrder()
            ->take(6)
            ->get();

        if ($propiedades->isEmpty()) {
            $propiedades = Property::query()
                ->where('status', 'published')
                ->with('city')
                ->inRandomOrder()
                ->take(6)
                ->get();
        }

        $ciudades = \App\Models\City::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('home', compact('propiedades', 'ciudades'));
    }

    public function verImagen(string $path)
    {
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $absolutePath = Storage::disk('public')->path($path);

        return response()->file($absolutePath, [
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
