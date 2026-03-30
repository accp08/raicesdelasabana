@extends('dashboard.layouts.app')

@section('title', 'Analítica')

@section('header')
    <div>
        <h2>Analítica del sitio</h2>
        <p class="text-muted">Páginas de entrada, clics en links, WhatsApp y campañas UTM.</p>
    </div>
@endsection

@section('content')
<div class="analytics-shell">
<div class="analytics-toolbar mb-4">
    <div class="analytics-note">
        <strong>Ideal para campañas de Ads:</strong> revisa páginas de entrada, clics en CTA y tráfico con `utm_campaign`.
    </div>
    <form method="GET" class="analytics-filters">
        <select name="range" class="form-select">
            <option value="7" {{ $range === 7 ? 'selected' : '' }}>Base 7 días</option>
            <option value="30" {{ $range === 30 ? 'selected' : '' }}>Base 30 días</option>
            <option value="90" {{ $range === 90 ? 'selected' : '' }}>Base 90 días</option>
        </select>
        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        <button class="btn btn-brand" type="submit">Filtrar</button>
    </form>
</div>

<div class="row g-4 dashboard-stats">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-icon">👁️</span>
                <h6>Páginas vistas</h6>
            </div>
            <span class="stat-value">{{ number_format($pageViews) }}</span>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-icon">🖱️</span>
                <h6>Clics en links</h6>
            </div>
            <span class="stat-value">{{ number_format($linkClicks) }}</span>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-icon">💬</span>
                <h6>Clics a WhatsApp</h6>
            </div>
            <span class="stat-value">{{ number_format($whatsappClicks) }}</span>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-icon">👤</span>
                <h6>Sesiones únicas</h6>
            </div>
            <span class="stat-value">{{ number_format($uniqueSessions) }}</span>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card stat-card-whatsapp">
            <div class="stat-head">
                <span class="stat-icon">🎯</span>
                <h6>Del clic a WhatsApp</h6>
            </div>
            <span class="stat-value">{{ number_format($whatsappClickRate, 1) }}%</span>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card stat-card-soft">
            <div class="stat-head">
                <span class="stat-icon">⚡</span>
                <h6>De vistas a WhatsApp</h6>
            </div>
            <span class="stat-value">{{ number_format($pageToWhatsappRate, 1) }}%</span>
        </div>
    </div>
</div>

<div class="analytics-panel analytics-chart-panel mt-4">
    <div class="analytics-panel-head">
        <div>
            <h5 class="mb-1">Rendimiento diario del periodo</h5>
            <p class="analytics-panel-subtitle mb-0">Barras horizontales por día para ver claramente el mes completo.</p>
        </div>
        <div class="trend-legend">
            <span><i class="legend-dot legend-dot-page"></i> Páginas vistas</span>
            <span><i class="legend-dot legend-dot-whatsapp"></i> WhatsApp</span>
            <span><i class="legend-dot legend-dot-rate"></i> Pico: {{ number_format($dailyMax) }}</span>
        </div>
    </div>
    <div class="analytics-panel-body">
        @if ($dailySeries->isEmpty())
            <p class="text-muted mb-0">Aún no hay suficiente información para graficar.</p>
        @else
            <div class="trend-chart-card trend-chart-card-wide">
                <div class="bars-chart bars-chart-wide" role="img" aria-label="Barras diarias de páginas vistas y clics a WhatsApp">
                    @for ($i = 0; $i < 4; $i++)
                        <span class="bars-grid-line" style="bottom: {{ 18 + ($i * 26) }}%;"></span>
                    @endfor
                    @foreach ($dailySeries as $point)
                        @php
                            $pageHeight = max(3, (int) round(($point['page_views'] / $dailyMax) * 100));
                            $whatsappHeight = max(3, (int) round(($point['whatsapp_clicks'] / $dailyMax) * 100));
                        @endphp
                        <div class="bars-day" title="{{ $point['label'] }} · {{ $point['page_views'] }} vistas · {{ $point['whatsapp_clicks'] }} WhatsApp">
                            <div class="bars-pair">
                                <span class="bars-bar bars-bar-page" style="height: {{ $pageHeight }}%"></span>
                                <span class="bars-bar bars-bar-whatsapp" style="height: {{ $whatsappHeight }}%"></span>
                            </div>
                            <span class="bars-day-label">{{ $point['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row g-4 mt-1">

    <div class="col-lg-6">
        <div class="card shadow-sm h-100 analytics-card">
            <div class="card-header analytics-card-header">
                <h5 class="mb-0">Páginas de entrada más visitadas</h5>
            </div>
            <div class="card-body">
                @if ($topLandingPages->isEmpty())
                    <p class="text-muted mb-0">Aún no hay eventos registrados.</p>
                @else
                    <div class="analytics-bars">
                        @foreach ($topLandingPages as $row)
                            @php
                                $width = max(6, (int) round(($row->total / $topLandingMax) * 100));
                            @endphp
                            <div class="analytics-bar-row">
                                <div class="analytics-bar-head">
                                    <span>{{ $row->page_path ?: '—' }}</span>
                                    <strong>{{ number_format($row->total) }}</strong>
                                </div>
                                <div class="analytics-bar-track">
                                    <span class="analytics-bar-fill" style="width: {{ $width }}%"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100 analytics-card">
            <div class="card-header analytics-card-header">
                <h5 class="mb-0">Campañas y UTMs</h5>
            </div>
            <div class="card-body">
                @if ($campaigns->isEmpty())
                    <p class="text-muted mb-0">Todavía no se detectan campañas con `utm_campaign`.</p>
                @else
                    <div class="analytics-bars">
                        @foreach ($campaigns as $campaign)
                            @php
                                $width = max(6, (int) round(($campaign->total / $campaignMax) * 100));
                            @endphp
                            <div class="analytics-bar-row">
                                <div class="analytics-bar-head analytics-bar-head-stack">
                                    <div>
                                        <span>{{ $campaign->utm_campaign }}</span>
                                        <small>{{ $campaign->utm_source ?: '—' }} / {{ $campaign->utm_medium ?: '—' }}</small>
                                    </div>
                                    <strong>{{ number_format($campaign->total) }}</strong>
                                </div>
                                <div class="analytics-bar-track">
                                    <span class="analytics-bar-fill analytics-bar-fill-campaign" style="width: {{ $width }}%"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100 analytics-card">
            <div class="card-header analytics-card-header">
                <h5 class="mb-0">Links más clicados</h5>
            </div>
            <div class="card-body">
                @if ($topClickedLinks->isEmpty())
                    <p class="text-muted mb-0">Aún no hay clics registrados.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Texto</th>
                                    <th>Destino</th>
                                    <th class="text-end">Clics</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topClickedLinks as $row)
                                    <tr>
                                        <td>{{ $row->target_text ?: 'Sin texto' }}</td>
                                        <td class="small text-muted">{{ \Illuminate\Support\Str::limit($row->target_url, 60) }}</td>
                                        <td class="text-end">{{ number_format($row->total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100 analytics-card">
            <div class="card-header analytics-card-header">
                <h5 class="mb-0">CTA de WhatsApp más usados</h5>
            </div>
            <div class="card-body">
                @if ($topWhatsappCtas->isEmpty())
                    <p class="text-muted mb-0">Aún no hay clics a WhatsApp registrados.</p>
                @else
                    <div class="analytics-bars">
                        @foreach ($topWhatsappCtas as $row)
                            @php
                                $width = max(6, (int) round(($row->total / $topWhatsappMax) * 100));
                            @endphp
                            <div class="analytics-bar-row">
                                <div class="analytics-bar-head analytics-bar-head-stack">
                                    <div>
                                        <span>{{ $row->cta_key ?: 'whatsapp' }}</span>
                                        <small>{{ $row->page_path ?: '—' }}</small>
                                    </div>
                                    <strong>{{ number_format($row->total) }}</strong>
                                </div>
                                <div class="analytics-bar-track">
                                    <span class="analytics-bar-fill analytics-bar-fill-whatsapp" style="width: {{ $width }}%"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4 analytics-card">
    <div class="card-header analytics-card-header">
        <h5 class="mb-0">Eventos recientes</h5>
    </div>
    <div class="card-body">
        @if ($recentEvents->isEmpty())
            <p class="text-muted mb-0">Aún no hay eventos recientes.</p>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Tipo</th>
                            <th>Página</th>
                            <th>CTA / destino</th>
                            <th>Campaña</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentEvents as $event)
                            <tr>
                                <td>{{ optional($event->occurred_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ $event->event_type }}</td>
                                <td>{{ $event->page_path ?: '—' }}</td>
                                <td>{{ $event->cta_key ?: \Illuminate\Support\Str::limit($event->target_url, 45) ?: '—' }}</td>
                                <td>{{ $event->utm_campaign ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
</div>
@endsection
