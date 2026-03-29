<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyLeadUpdateRequest;
use App\Models\PropertyLead;

class PropertyLeadsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PropertyLead::class, 'lead');
    }

    public function index()
    {
        $query = PropertyLead::with('property')->orderByDesc('created_at');

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($propertyId = request('property_id')) {
            $query->where('property_id', $propertyId);
        }

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(20)->withQueryString();
        $properties = \App\Models\Property::orderBy('titulo')->get(['id', 'titulo']);

        return view('dashboard.leads.index', compact('leads', 'properties'));
    }

    public function show(PropertyLead $lead)
    {
        $lead->load('property');

        return view('dashboard.leads.show', compact('lead'));
    }

    public function update(PropertyLeadUpdateRequest $request, PropertyLead $lead)
    {
        $lead->update($request->validated());

        return redirect()->route('dashboard.leads.show', $lead)->with('status', 'Estado actualizado.');
    }

    public function destroy(PropertyLead $lead)
    {
        $lead->delete();

        return redirect()->route('dashboard.leads.index')->with('status', 'Contacto eliminado.');
    }
}
