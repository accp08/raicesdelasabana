<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityStoreRequest;
use App\Http\Requests\CityUpdateRequest;
use App\Models\City;
use Illuminate\Support\Str;

class CitiesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(City::class, 'city');
    }

    public function index()
    {
        $cities = City::orderBy('name')->paginate(20);

        return view('dashboard.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('dashboard.cities.create');
    }

    public function store(CityStoreRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        City::create($data);

        return redirect()->route('dashboard.cities.index')->with('status', 'Ciudad creada.');
    }

    public function edit(City $city)
    {
        return view('dashboard.cities.edit', compact('city'));
    }

    public function update(CityUpdateRequest $request, City $city)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $city->update($data);

        return redirect()->route('dashboard.cities.index')->with('status', 'Ciudad actualizada.');
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('dashboard.cities.index')->with('status', 'Ciudad eliminada.');
    }
}
