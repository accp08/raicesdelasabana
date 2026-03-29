<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertyLeadStoreRequest;
use App\Mail\PropertyLeadCreated;
use App\Models\Property;
use App\Models\PropertyLead;
use Illuminate\Support\Facades\Mail;

class PropertyLeadController extends Controller
{
    public function store(PropertyLeadStoreRequest $request, string $slug)
    {
        $property = Property::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $lead = PropertyLead::create([
            'property_id' => $property->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
            'source_page' => url()->current(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        $recipient = config('mail.leads_to') ?? env('LEADS_EMAIL') ?? env('ADMIN_EMAIL') ?? config('mail.from.address');
        if ($recipient) {
            try {
                Mail::to($recipient)->send(new PropertyLeadCreated($lead));
            } catch (\Throwable $e) {
                // Avoid failing user flow if mail is not configured.
            }
        }

        return back()->with('status', 'Gracias, recibimos tu solicitud. Te contactaremos pronto.');
    }
}
