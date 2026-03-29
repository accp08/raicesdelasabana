@extends('layouts.app')

@section('title', 'Raíces de la Sabana | Compra, venta y arriendo en la Sabana de Bogotá')
@section('meta_description', 'Inmuebles en la Sabana de Bogotá. Compra, venta y arriendo con asesoría experta, visitas guiadas y acompañamiento legal.')
@section('meta_og_image', asset('img/fondo-campo.jpg'))
@section('meta_twitter_image', asset('img/fondo-campo.jpg'))

@push('structured_data')
@php
    $homeWhatsappMessage = 'Hola, quiero información sobre inmuebles disponibles en la Sabana de Bogotá.';
    $homeWhatsappHref = 'https://wa.me/573150597595?text=' . rawurlencode($homeWhatsappMessage);
    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateAgent',
        'name' => 'Raíces de la Sabana',
        'url' => config('app.url'),
        'telephone' => '+57 3150597595',
        'email' => 'contacto@raicesdelasabana.com',
        'areaServed' => 'Sabana de Bogotá, Cundinamarca',
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+57 3150597595',
            'contactType' => 'sales',
            'areaServed' => 'CO',
            'availableLanguage' => ['es', 'en'],
        ],
    ];
    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Raíces de la Sabana',
        'url' => config('app.url'),
        'inLanguage' => 'es-CO',
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Raíces de la Sabana',
            'url' => config('app.url'),
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content text-center text-lg-start">
            <span class="hero-kicker">Inmuebles en la Sabana de Bogotá</span>
            <h1 class="display-5 fw-bold">Encuentra tu lugar ideal para vivir o invertir</h1>
            <p class="lead">Acompañamiento experto, inmuebles verificados y negociación transparente.</p>

            <div class="hero-actions">
                <a href="{{ url('/propiedades') }}" class="btn btn-brand">Ver propiedades</a>
                <a href="{{ $homeWhatsappHref }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-brand">Hablar por WhatsApp</a>
            </div>

            <div class="search-panel">
                <div class="search-panel__head">
                    <h5>Busca tu propiedad</h5>
                    <span>Filtra rápido y compara opciones</span>
                </div>
                <form class="row g-2 align-items-center" method="GET" action="{{ route('propiedades.index') }}">
                    <div class="col-md-4">
                        <select class="form-select" name="tipo">
                            <option value="">¿Venta o Arriendo?</option>
                            <option value="venta">Venta</option>
                            <option value="arriendo">Arriendo</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select class="form-select" name="city_id">
                            <option value="">Selecciona una ciudad</option>
                            @foreach ($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}">{{ $ciudad->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-danger w-100 btn-search">Buscar ahora</button>
                    </div>
                </form>
            </div>

            <div class="trust-row">
                <div class="trust-item">+350 familias asesoradas</div>
                <div class="trust-item">Visitas guiadas en 24-48h</div>
                <div class="trust-item">Acompañamiento legal completo</div>
            </div>
        </div>
    </div>
</section>

<!-- Propiedades destacadas -->
<section class="container py-5">
    <div class="section-head text-center">
        <h2 class="section-title">Propiedades Destacadas</h2>
        <p class="section-subtitle">Opciones premium seleccionadas por nuestro equipo.</p>
    </div>

    @if ($propiedades->isNotEmpty())
        <div class="row g-4">
            @foreach ($propiedades as $propiedad)
                @php
                    if ($propiedad->for_sale && $propiedad->for_rent) {
                        $badgeClass = 'badge-destacado';
                        $badgeLabel = 'Venta y arriendo';
                    } elseif ($propiedad->for_sale) {
                        $badgeClass = 'badge-venta';
                        $badgeLabel = 'Venta';
                    } else {
                        $badgeClass = 'badge-arriendo';
                        $badgeLabel = 'Arriendo';
                    }
                    $image = $propiedad->imagen_principal_thumb
                        ? Storage::url($propiedad->imagen_principal_thumb)
                        : ($propiedad->imagen_principal ? Storage::url($propiedad->imagen_principal) : asset('img/fondo-campo.jpg'));
                @endphp
                <div class="col-md-4">
                    <div class="card shadow-sm h-100 card-property">
                        <div class="position-relative">
                            <img src="{{ $image }}" class="card-img-top" alt="{{ $propiedad->titulo }}" loading="lazy" decoding="async">
                            <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                            @if ($propiedad->is_featured)
                                <span class="badge badge-featured">⭐ Destacada</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <p class="card-meta mb-2">{{ $propiedad->city?->name ?? $propiedad->ciudad }}</p>
                            <h5 class="card-title fw-bold">{{ $propiedad->titulo }}</h5>
                            @if ($propiedad->area_m2)
                                <p class="card-meta mb-2">Área: {{ number_format((float) $propiedad->area_m2, 1, ',', '.') }} m²</p>
                            @endif
                            <p class="text-danger fw-bold">
                                @if ($propiedad->for_sale && $propiedad->sale_price)
                                    Venta: ${{ number_format($propiedad->sale_price, 0, ',', '.') }}
                                @endif
                                @if ($propiedad->for_rent && $propiedad->rent_price)
                                    <span class="d-block">Arriendo: ${{ number_format($propiedad->rent_price, 0, ',', '.') }}</span>
                                @endif
                                @if (! $propiedad->for_sale && ! $propiedad->for_rent)
                                    {{ $propiedad->precio ? '$'.number_format($propiedad->precio, 0, ',', '.') : 'Precio bajo consulta' }}
                                @endif
                            </p>
                            <a href="{{ route('propiedades.show', $propiedad->slug) }}" class="btn btn-outline-danger w-100">Ver detalles</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning text-center">
            No se encontraron propiedades para mostrar.
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ url('/propiedades') }}" class="btn btn-outline-danger fw-bold px-4">
            Ver más inmuebles
        </a>
    </div>
</section>

<!-- Beneficios -->
<section class="section-soft">
    <div class="container">
        <div class="section-head text-center">
            <h2 class="section-title">Tu compra o arriendo, sin fricciones</h2>
            <p class="section-subtitle">Creamos una experiencia clara y confiable para tomar la mejor decisión.</p>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="info-card">
                    <h5>Selección curada</h5>
                    <p>Solo mostramos inmuebles con documentación y precios alineados al mercado.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card">
                    <h5>Asesoría estratégica</h5>
                    <p>Te guiamos con análisis de valorización, entorno y proyección.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card">
                    <h5>Gestión integral</h5>
                    <p>Coordinamos visitas, negociación y trámites para que todo fluya.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA final -->
<section class="cta-band">
    <div class="container">
        <div class="cta-card">
            <div>
                <h3>¿Listo para agendar una visita?</h3>
                <p>Agenda hoy y recibe una propuesta personalizada con opciones que sí encajan contigo.</p>
            </div>
            <div class="cta-actions">
                <a href="{{ $homeWhatsappHref }}" target="_blank" rel="noopener noreferrer" class="btn btn-light">Escribir por WhatsApp</a>
                <a href="tel:+573150597595" class="btn btn-outline-light">Llamar ahora</a>
            </div>
        </div>
    </div>
</section>

@endsection
