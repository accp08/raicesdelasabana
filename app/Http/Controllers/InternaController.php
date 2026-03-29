<?php

namespace App\Http\Controllers;

use App\Models\Property;

class InternaController extends Controller
{
    public function index()
    {
        $property = Property::where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->first();

        if (! $property) {
            return redirect()->route('propiedades.index')
                ->with('status', 'No hay propiedades publicadas.');
        }

        return view('interna', compact('property'));
    }

}
