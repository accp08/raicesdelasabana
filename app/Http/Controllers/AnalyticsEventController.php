<?php

namespace App\Http\Controllers;

use App\Models\SiteAnalyticsEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsEventController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'event_type' => ['required', 'string', 'in:page_view,link_click'],
            'page_path' => ['nullable', 'string', 'max:255'],
            'page_url' => ['nullable', 'string', 'max:2000'],
            'target_url' => ['nullable', 'string', 'max:2000'],
            'target_text' => ['nullable', 'string', 'max:255'],
            'cta_key' => ['nullable', 'string', 'max:120'],
            'referrer' => ['nullable', 'string', 'max:2000'],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'utm_content' => ['nullable', 'string', 'max:255'],
            'utm_term' => ['nullable', 'string', 'max:255'],
        ]);

        $data['session_id'] = $request->session()->getId();
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = (string) $request->userAgent();
        $data['occurred_at'] = now();

        SiteAnalyticsEvent::create($data);

        return response()->json(['ok' => true]);
    }
}
