<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteAnalyticsEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $range = (int) $request->integer('range', 30);
        if (!in_array($range, [7, 30, 90], true)) {
            $range = 30;
        }

        $defaultStartDate = now()->subDays($range - 1)->toDateString();
        $defaultEndDate = now()->toDateString();

        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate = $request->input('end_date', $defaultEndDate);

        try {
            $from = Carbon::parse($startDate)->startOfDay();
        } catch (\Throwable) {
            $startDate = $defaultStartDate;
            $from = Carbon::parse($startDate)->startOfDay();
        }

        try {
            $to = Carbon::parse($endDate)->endOfDay();
        } catch (\Throwable) {
            $endDate = $defaultEndDate;
            $to = Carbon::parse($endDate)->endOfDay();
        }

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
            [$startDate, $endDate] = [$from->toDateString(), $to->toDateString()];
        }

        $baseQuery = SiteAnalyticsEvent::query()
            ->whereBetween('occurred_at', [$from, $to]);

        $pageViews = (clone $baseQuery)->where('event_type', 'page_view')->count();
        $linkClicks = (clone $baseQuery)->where('event_type', 'link_click')->count();
        $whatsappClicks = (clone $baseQuery)
            ->where('event_type', 'link_click')
            ->where(function ($query) {
                $query->where('target_url', 'like', '%wa.me/%')
                    ->orWhere('cta_key', 'like', '%whatsapp%');
            })
            ->count();
        $uniqueSessions = (clone $baseQuery)->distinct('session_id')->count('session_id');

        $topLandingPages = (clone $baseQuery)
            ->select('page_path', DB::raw('COUNT(*) as total'))
            ->where('event_type', 'page_view')
            ->groupBy('page_path')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topClickedLinks = (clone $baseQuery)
            ->select('target_url', 'target_text', DB::raw('COUNT(*) as total'))
            ->where('event_type', 'link_click')
            ->groupBy('target_url', 'target_text')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        $topWhatsappCtas = (clone $baseQuery)
            ->select('page_path', 'cta_key', DB::raw('COUNT(*) as total'))
            ->where('event_type', 'link_click')
            ->where(function ($query) {
                $query->where('target_url', 'like', '%wa.me/%')
                    ->orWhere('cta_key', 'like', '%whatsapp%');
            })
            ->groupBy('page_path', 'cta_key')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        $campaigns = (clone $baseQuery)
            ->select(
                'utm_source',
                'utm_medium',
                'utm_campaign',
                DB::raw('COUNT(*) as total')
            )
            ->where('event_type', 'page_view')
            ->whereNotNull('utm_campaign')
            ->groupBy('utm_source', 'utm_medium', 'utm_campaign')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        $recentEvents = (clone $baseQuery)
            ->latest('occurred_at')
            ->limit(20)
            ->get();

        $dailyPageViewsRaw = (clone $baseQuery)
            ->selectRaw('DATE(occurred_at) as event_date, COUNT(*) as total')
            ->where('event_type', 'page_view')
            ->groupBy('event_date')
            ->orderBy('event_date')
            ->get()
            ->keyBy('event_date');

        $dailyWhatsappRaw = (clone $baseQuery)
            ->selectRaw('DATE(occurred_at) as event_date, COUNT(*) as total')
            ->where('event_type', 'link_click')
            ->where(function ($query) {
                $query->where('target_url', 'like', '%wa.me/%')
                    ->orWhere('cta_key', 'like', '%whatsapp%');
            })
            ->groupBy('event_date')
            ->orderBy('event_date')
            ->get()
            ->keyBy('event_date');

        $dailySeries = collect();
        $cursor = $from->copy()->startOfDay();
        $endCursor = $to->copy()->startOfDay();

        while ($cursor->lessThanOrEqualTo($endCursor)) {
            $key = $cursor->toDateString();
            $dailySeries->push([
                'date' => $key,
                'label' => $cursor->locale('es')->translatedFormat('d M'),
                'page_views' => (int) ($dailyPageViewsRaw[$key]->total ?? 0),
                'whatsapp_clicks' => (int) ($dailyWhatsappRaw[$key]->total ?? 0),
            ]);
            $cursor->addDay();
        }

        $dailyMax = max(
            1,
            (int) $dailySeries->max('page_views'),
            (int) $dailySeries->max('whatsapp_clicks')
        );
        $topLandingMax = max(1, (int) $topLandingPages->max('total'));
        $topWhatsappMax = max(1, (int) $topWhatsappCtas->max('total'));
        $campaignMax = max(1, (int) $campaigns->max('total'));
        $whatsappClickRate = $linkClicks > 0 ? round(($whatsappClicks / $linkClicks) * 100, 1) : 0;
        $pageToWhatsappRate = $pageViews > 0 ? round(($whatsappClicks / $pageViews) * 100, 1) : 0;

        return view('dashboard.analytics.index', compact(
            'range',
            'startDate',
            'endDate',
            'pageViews',
            'linkClicks',
            'whatsappClicks',
            'uniqueSessions',
            'topLandingPages',
            'topClickedLinks',
            'topWhatsappCtas',
            'campaigns',
            'recentEvents',
            'dailySeries',
            'dailyMax',
            'topLandingMax',
            'topWhatsappMax',
            'campaignMax',
            'whatsappClickRate',
            'pageToWhatsappRate'
        ));
    }
}
