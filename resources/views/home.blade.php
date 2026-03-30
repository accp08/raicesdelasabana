@extends('layouts.app')

@section('title', 'Inmobiliaria en la Sabana de Bogotá | Raíces de la Sabana')
@section('meta_description', 'Inmuebles en la Sabana de Bogotá. Compra, venta y arriendo con asesoría experta, visitas guiadas y acompañamiento legal.')
@section('meta_og_image', asset('img/fondo-campo.jpg'))
@section('meta_twitter_image', asset('img/fondo-campo.jpg'))
@section('whatsapp_link', $homeWhatsappHref ?? 'https://wa.me/573150597595?text=Hola%2C%20quiero%20informaci%C3%B3n%20sobre%20inmuebles%20disponibles%20en%20la%20Sabana%20de%20Bogot%C3%A1.')
@section('whatsapp_title', 'Hablar con un asesor por WhatsApp')
@section('whatsapp_subtitle', 'Cuéntanos qué ciudad, presupuesto y tipo de inmueble buscas y te compartimos opciones reales.')

@push('seo_links')
<link rel="preload" as="image" href="{{ asset('img/fondo-campo.jpg') }}" fetchpriority="high">
@endpush

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
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => route('propiedades.index') . '?city_id={city_id}&tipo={tipo}',
            'query-input' => [
                'required name=city_id',
                'required name=tipo',
            ],
        ],
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
                    <h2 class="search-panel__title">Busca tu propiedad</h2>
                    <span>Filtra rápido y compara opciones</span>
                </div>
                <form class="row g-2 align-items-center" method="GET" action="{{ route('propiedades.index') }}">
                    <div class="col-md-4">
                        <label class="visually-hidden" for="home-search-tipo">Tipo de negocio</label>
                        <select class="form-select" id="home-search-tipo" name="tipo">
                            <option value="">¿Venta o Arriendo?</option>
                            <option value="venta">Venta</option>
                            <option value="arriendo">Arriendo</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="visually-hidden" for="home-search-city">Ciudad</label>
                        <select class="form-select" id="home-search-city" name="city_id">
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
<section class="container py-5 defer-section">
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
                            <img src="{{ $image }}" class="card-img-top" alt="{{ $propiedad->titulo }}" width="640" height="360" loading="lazy" decoding="async">
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
                                    Venta: {{ $propiedad->formatMoney((float) $propiedad->sale_price, $propiedad->sale_currency) }}
                                @endif
                                @if ($propiedad->for_rent && $propiedad->rent_price)
                                    <span class="d-block">Arriendo: {{ $propiedad->formatMoney((float) $propiedad->rent_price, $propiedad->rent_currency) }}</span>
                                @endif
                                @if (! $propiedad->for_sale && ! $propiedad->for_rent)
                                    {{ $propiedad->precio ? $propiedad->formatMoney((float) $propiedad->precio, 'COP') : 'Precio bajo consulta' }}
                                @endif
                            </p>
                            @php
                                $cardWhatsappMessage = "Hola, quiero información sobre el inmueble {$propiedad->titulo} en ".($propiedad->city?->name ?? $propiedad->ciudad).". Link: ".route('propiedades.show', $propiedad->slug);
                                $cardWhatsappHref = 'https://wa.me/573150597595?text=' . rawurlencode($cardWhatsappMessage);
                            @endphp
                            <div class="card-cta-stack">
                                <a href="{{ route('propiedades.show', $propiedad->slug) }}" class="btn btn-outline-danger w-100">Ver detalles</a>
                                <a href="{{ $cardWhatsappHref }}" target="_blank" rel="noopener noreferrer" class="btn btn-danger w-100">Consultar por WhatsApp</a>
                            </div>
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
<section class="section-soft defer-section">
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
<section class="cta-band defer-section">
    <div class="container">
        <div class="cta-card">
            <div>
                <h2>¿Listo para agendar una visita?</h2>
                <p>Agenda hoy y recibe una propuesta personalizada con opciones que sí encajan contigo.</p>
            </div>
            <div class="cta-actions">
                <a href="{{ $homeWhatsappHref }}" target="_blank" rel="noopener noreferrer" class="btn btn-light">Escribir por WhatsApp</a>
                <a href="{{ url('/propiedades') }}" class="btn btn-outline-light">Ver inmuebles disponibles</a>
            </div>
        </div>
    </div>
</section>

<section class="container py-5 defer-section">
    <div class="section-head text-center">
        <h2 class="section-title">Compra, arriendo e inversión inmobiliaria en la Sabana de Bogotá</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <p class="lead text-center mb-4">
                En Raíces de la Sabana ayudamos a personas, familias e inversionistas a encontrar inmuebles bien ubicados, con información clara y acompañamiento experto en cada etapa del proceso.
            </p>
            <p>
                Nuestro portafolio incluye casas, apartamentos, lotes, fincas, oficinas y bodegas en zonas de alta demanda como Chía, Cajicá, Zipaquirá, Bogotá y otros municipios de la Sabana. Si quieres vivir con mejor calidad de vida, mudarte cerca de Bogotá o invertir en una propiedad con potencial de valorización, te orientamos con opciones reales y actualizadas.
            </p>
            <p>
                Además de mostrar inmuebles disponibles para compra y arriendo, te acompañamos en visitas, negociación, validación comercial y toma de decisión. Nuestro objetivo es que encuentres tu lugar ideal para vivir o invertir con un proceso más claro, confiable y rápido. Si prefieres una atención directa, puedes escribirnos por WhatsApp y uno de nuestros asesores te ayudará a filtrar propiedades según ciudad, presupuesto y tipo de inmueble.
            </p>
        </div>
    </div>
</section>

@endsection
