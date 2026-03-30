@extends('layouts.app')
@push('page_styles')
<link href="{{ asset('css/nosotros.css?id=1') }}" rel="stylesheet" />
<link href="{{ asset('css/propiedades.css?id=1') }}" rel="stylesheet" />
@endpush 

@push('seo_links')
<link rel="preload" as="image" href="{{ asset('img/banner-propiedades.jpeg') }}" fetchpriority="high">
@endpush

@php
    $hasFilters = request()->filled('city_id')
        || request()->filled('tipo')
        || filled(array_filter((array) request('property_type', [])));
    $isPaginated = $propiedades->currentPage() > 1;
    $hasSeed = request()->filled('seed');
    $selectedCity = $ciudades->firstWhere('id', (int) request('city_id'));
    $selectedOffer = match (request('tipo')) {
        'venta' => 'en venta',
        'arriendo' => 'en arriendo',
        default => 'disponibles',
    };
    $pageLabel = $isPaginated ? ' - Página '.$propiedades->currentPage() : '';
    $canonicalUrl = $isPaginated
        ? route('propiedades.index', ['page' => $propiedades->currentPage()])
        : route('propiedades.index');
    $seoTitle = 'Propiedades '.$selectedOffer;
    if ($selectedCity) {
        $seoTitle .= ' en '.$selectedCity->name;
    } else {
        $seoTitle .= ' en la Sabana de Bogotá';
    }
    $seoTitle .= ' | Raíces de la Sabana'.$pageLabel;

    $seoDescription = 'Explora inmuebles '.$selectedOffer;
    if ($selectedCity) {
        $seoDescription .= ' en '.$selectedCity->name;
    } else {
        $seoDescription .= ' en la Sabana de Bogotá';
    }
    $seoDescription .= '. Encuentra casas, apartamentos, lotes y más con fichas completas e información actualizada.';
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical', $canonicalUrl)
@section('meta_robots', ($hasFilters || $hasSeed || $isPaginated) ? 'noindex,follow' : 'index,follow')
@section('whatsapp_link', 'https://wa.me/573150597595?text='.rawurlencode('Hola, quiero ayuda para encontrar un inmueble en la Sabana de Bogotá. Estoy viendo el listado de propiedades: '.route('propiedades.index')))
@section('whatsapp_title', 'Quiero ayuda para encontrar mi inmueble')
@section('whatsapp_subtitle', 'Te ayudamos a filtrar por ciudad, tipo de negocio y presupuesto para enviarte opciones por WhatsApp.')

@push('seo_links')
    @if ($propiedades->previousPageUrl())
<link rel="prev" href="{{ route('propiedades.index', ['page' => $propiedades->currentPage() - 1]) }}">
    @endif
    @if ($propiedades->nextPageUrl())
<link rel="next" href="{{ route('propiedades.index', ['page' => $propiedades->currentPage() + 1]) }}">
    @endif
@endpush

@push('structured_data')
@php
    $breadcrumbs = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Inicio',
                'item' => url('/'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Propiedades',
                'item' => route('propiedades.index'),
            ],
        ],
    ];
    $itemList = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $seoTitle,
        'description' => $seoDescription,
        'url' => $canonicalUrl,
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => $propiedades->count(),
            'itemListElement' => $propiedades->values()->map(function ($property, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => route('propiedades.show', $property->slug),
                    'name' => $property->seo_title ?: $property->titulo,
                ];
            })->all(),
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($breadcrumbs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($itemList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')


<section id="s-propiedades">
    <div id="content-propiedades">
        <div id="content-filtro-propiedades">
            <button class="filters-collapse-btn" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                Mostrar filtros
            </button>
            <div class="collapse d-lg-block" id="filtersCollapse">
            <form action="" method="GET" class="filters-form">
                <input type="hidden" name="seed" value="{{ $randomSeed }}">
                <div class="filters-section">
                    <p class="filters-title">Oferta</p>
                    <div class="offer-toggle">
                        <label class="offer-card">
                            <input type="radio" name="tipo" value="arriendo" {{ request('tipo') === 'arriendo' ? 'checked' : '' }}>
                            <span class="offer-icon">🏠</span>
                            <span class="offer-text">Arrendar</span>
                        </label>
                        <label class="offer-card">
                            <input type="radio" name="tipo" value="venta" {{ request('tipo') === 'venta' ? 'checked' : '' }}>
                            <span class="offer-icon">🏡</span>
                            <span class="offer-text">Comprar</span>
                        </label>
                    </div>
                </div>

                <div class="content-input">
                    <label for="propiedades-city">Ciudad</label>
                    <select id="propiedades-city" name="city_id" class="form-select">
                        <option value="">- Seleccionar -</option>
                    @foreach ($ciudades as $ciudad)
                        <option value="{{ $ciudad->id }}" {{ request('city_id') == $ciudad->id ? 'selected' : '' }}>
                            {{ $ciudad->name }}
                        </option>
                    @endforeach
                </select>
            </div>

                <div class="content-input">
                    <span class="content-label">Tipo de inmueble</span>
                    <div class="type-list">
                        @php
                            $types = [
                                'Apartamento' => '🏢',
                                'Casa' => '🏠',
                                'Lote' => '🌿',
                                'Casa Lote' => '🏡',
                                'Oficina' => '🏢',
                                'Bodega' => '🏭',
                                'Finca' => '🌾',
                            ];
                            $selectedTypes = (array) request('property_type', []);
                        @endphp
                        @foreach (array_slice($types, 0, 2, true) as $type => $icon)
                            <label class="type-item">
                                <span class="type-icon">{{ $icon }}</span>
                                <span class="type-label">{{ $type }}</span>
                                <input type="checkbox" name="property_type[]" value="{{ $type }}" {{ in_array($type, $selectedTypes, true) ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                            </label>
                        @endforeach
                        <div class="collapse" id="moreTypes">
                            @foreach (array_slice($types, 2, null, true) as $type => $icon)
                                <label class="type-item">
                                    <span class="type-icon">{{ $icon }}</span>
                                    <span class="type-label">{{ $type }}</span>
                                    <input type="checkbox" name="property_type[]" value="{{ $type }}" {{ in_array($type, $selectedTypes, true) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            @endforeach
                        </div>
                        <button class="btn btn-light w-100 more-types-btn" type="button" data-bs-toggle="collapse" data-bs-target="#moreTypes">
                            Más tipos
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-danger w-100" type="submit">Aplicar filtros</button>
                    <a href="{{ route('propiedades.index') }}" class="btn btn-light w-100">Limpiar</a>
                </div>
            </form>
            </div>
        </div>
        <div class="propiedades-content">
            <div class="mb-4">
                <h1 class="h3 fw-bold">Propiedades disponibles</h1>
                <p class="text-muted">Filtra por ciudad y tipo para encontrar la opción ideal.</p>
                <div class="listing-cta-card">
                    <div>
                        <strong>¿Prefieres que te enviemos opciones por WhatsApp?</strong>
                        <p>Cuéntanos qué ciudad, presupuesto y tipo de inmueble buscas, y te compartimos alternativas más rápido.</p>
                    </div>
                    <a href="@yield('whatsapp_link')" target="_blank" rel="noopener noreferrer" class="btn btn-danger">Recibir opciones por WhatsApp</a>
                </div>
            </div>

            <div id="content-cards-propiedades">
                @if ($propiedades->isNotEmpty())
                    <div class="row g-4" id="cardsGrid" data-next-page="{{ $propiedades->nextPageUrl() }}">
                        @include('propiedades.partials.cards', ['propiedades' => $propiedades])
                    </div>
                    <div id="infiniteLoader" class="infinite-loader d-none">Cargando más inmuebles...</div>
                    <div id="infiniteStatus" class="infinite-status" aria-live="polite"></div>
                    <div id="infiniteSentinel" class="infinite-sentinel"></div>
                    <div class="mt-4">
                        {{ $propiedades->onEachSide(1)->links() }}
                    </div>
                @else
                    <div class="alert alert-warning text-center">
                        No se encontraron propiedades para mostrar.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('cardsGrid');
    const sentinel = document.getElementById('infiniteSentinel');
    const loader = document.getElementById('infiniteLoader');
    const status = document.getElementById('infiniteStatus');
    if (!grid || !sentinel || !loader || !status) return;

    let nextPageUrl = grid.dataset.nextPage;
    let isLoading = false;
    let observer = null;

    const stopLoading = (message = '') => {
        if (observer) observer.disconnect();
        if (message) status.textContent = message;
    };

    const loadNextPage = async () => {
        if (!nextPageUrl || isLoading) {
            if (!nextPageUrl) stopLoading('No hay más inmuebles para mostrar.');
            return;
        }

        isLoading = true;
        loader.classList.remove('d-none');

        try {
            const response = await fetch(nextPageUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) throw new Error('No se pudo cargar la siguiente página.');
            const data = await response.json();

            if (data.html) {
                grid.insertAdjacentHTML('beforeend', data.html);
            }

            nextPageUrl = data.next_page_url || null;
            grid.dataset.nextPage = nextPageUrl || '';

            if (!nextPageUrl) {
                stopLoading('No hay más inmuebles para mostrar.');
            }
        } catch (error) {
            stopLoading('Ocurrió un problema cargando más inmuebles.');
        } finally {
            isLoading = false;
            loader.classList.add('d-none');
        }
    };

    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                loadNextPage();
            }
        });
    }, {
        root: null,
        rootMargin: '500px 0px',
        threshold: 0.01,
    });

    observer.observe(sentinel);
});
</script>
@endpush
