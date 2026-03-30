@extends('layouts.app')
@push('page_styles')
<link href="{{ asset('css/interna.css?id=1') }}" rel="stylesheet" />
@endpush 

@php
    use Illuminate\Support\Str;
@endphp

@section('title', ($property->seo_title ?? $property->titulo).' | Raíces de la Sabana')
@section('meta_description', $property->seo_description ?? $property->descripcion_corta ?? Str::limit($property->descripcion ?? '', 160))
@section('canonical', route('propiedades.show', $property->slug))
@section('meta_og_type', 'product')
@section('meta_og_image', $property->imagen_principal ? Storage::url($property->imagen_principal) : asset('img/fondo-campo.jpg'))
@section('meta_twitter_image', $property->imagen_principal ? Storage::url($property->imagen_principal) : asset('img/fondo-campo.jpg'))
@php
    $propertyReference = $property->property_code ?: strtoupper($property->slug);
    $whatsappMessage = "Hola, estoy interesado en este inmueble: {$property->titulo}. Referencia: {$propertyReference}. Link: " . url()->current();
    $whatsappVisitMessage = "Hola, quiero agendar una visita para el inmueble {$property->titulo}. Referencia: {$propertyReference}. Link: " . url()->current();
    $whatsappAvailabilityMessage = "Hola, quiero confirmar disponibilidad del inmueble {$property->titulo}. Referencia: {$propertyReference}. Link: " . url()->current();
    $whatsappLocationMessage = "Hola, quiero recibir la ubicación del inmueble {$property->titulo}. Referencia: {$propertyReference}. Link: " . url()->current();
    $whatsappSimilarMessage = "Hola, quiero opciones similares al inmueble {$property->titulo}. Referencia: {$propertyReference}. Link: " . url()->current();
    $whatsappGeneralLink = 'https://wa.me/573150597595?text=' . rawurlencode($whatsappMessage);
    $whatsappVisitLink = 'https://wa.me/573150597595?text=' . rawurlencode($whatsappVisitMessage);
    $whatsappAvailabilityLink = 'https://wa.me/573150597595?text=' . rawurlencode($whatsappAvailabilityMessage);
    $whatsappLocationLink = 'https://wa.me/573150597595?text=' . rawurlencode($whatsappLocationMessage);
    $whatsappSimilarLink = 'https://wa.me/573150597595?text=' . rawurlencode($whatsappSimilarMessage);
    $propertyImages = collect([$property->imagen_principal, ...($property->galeria ?? [])])
        ->filter()
        ->map(fn ($image) => Storage::url($image))
        ->unique()
        ->values()
        ->all();
    $propertyPrice = $property->for_sale && $property->sale_price
        ? (float) $property->sale_price
        : (($property->for_rent && $property->rent_price) ? (float) $property->rent_price : (float) ($property->precio ?? 0));
    $propertyBusinessType = $property->for_sale ? 'https://schema.org/SellAction' : 'https://schema.org/RentAction';
@endphp
@section('whatsapp_link', $whatsappGeneralLink)
@section('whatsapp_title', 'Consultar este inmueble por WhatsApp')

@push('structured_data')
@php
    $propertyBreadcrumbs = [
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
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $property->titulo,
                'item' => route('propiedades.show', $property->slug),
            ],
        ],
    ];
    $realEstateSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'RealEstateListing',
        'name' => $property->seo_title ?: $property->titulo,
        'description' => strip_tags($property->seo_description ?: $property->descripcion_corta ?: $property->descripcion ?: $property->titulo),
        'url' => route('propiedades.show', $property->slug),
        'image' => $propertyImages,
        'datePosted' => optional($property->published_at ?: $property->created_at)->toAtomString(),
        'category' => $property->property_type,
        'mainEntity' => [
            '@type' => 'Residence',
            'name' => $property->titulo,
            'description' => strip_tags($property->descripcion_corta ?: $property->descripcion ?: $property->titulo),
            'floorSize' => $property->area_m2 ? [
                '@type' => 'QuantitativeValue',
                'value' => (float) $property->area_m2,
                'unitCode' => 'MTK',
            ] : null,
            'numberOfRooms' => $property->habitaciones ?: null,
            'numberOfBathroomsTotal' => $property->banos ?: null,
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $property->city?->name ?? $property->ciudad,
                'addressRegion' => 'Cundinamarca',
                'addressCountry' => 'CO',
                'streetAddress' => $property->direccion,
            ],
        ],
        'offers' => $propertyPrice > 0 ? [
            '@type' => 'Offer',
            'price' => $propertyPrice,
            'priceCurrency' => 'COP',
            'availability' => 'https://schema.org/InStock',
            'businessFunction' => $propertyBusinessType,
            'url' => route('propiedades.show', $property->slug),
        ] : null,
        'seller' => [
            '@type' => 'RealEstateAgent',
            'name' => 'Raíces de la Sabana',
            'url' => config('app.url'),
            'telephone' => '+57 3150597595',
        ],
    ];

    $realEstateSchema = array_filter($realEstateSchema, fn ($value) => !is_null($value) && $value !== []);
    $realEstateSchema['mainEntity'] = array_filter($realEstateSchema['mainEntity'], fn ($value) => !is_null($value) && $value !== []);
@endphp
<script type="application/ld+json">{!! json_encode($propertyBreadcrumbs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($realEstateSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
@php
    $badgeLabel = $property->tipo === 'venta' ? 'En venta' : 'En arriendo';
@endphp

<section id="s-info-interna" class="property-shell">
    <div id="content-info-interna" class="property-content">
        <div class="property-head">
            <div class="property-tags">
                <span class="color-arriendo">{{ $badgeLabel }}</span>
                <span class="color-estandar">{{ $property->city?->name ?? $property->ciudad }}</span>
                <span class="color-estandar">{{ $property->titulo }}</span>
                @if ($property->property_code)
                    <span class="color-estandar">Código {{ $property->property_code }}</span>
                @endif
            </div>
            <div class="property-price">
                @if ($property->for_sale && $property->sale_price)
                    <div><strong>Venta:</strong> {{ $property->formatMoney((float) $property->sale_price, $property->sale_currency) }}</div>
                @endif
                @if ($property->for_rent && $property->rent_price)
                    <div><strong>Arriendo:</strong> {{ $property->formatMoney((float) $property->rent_price, $property->rent_currency) }}</div>
                @endif
                @if (! $property->for_sale && ! $property->for_rent)
                    {{ $property->precio ? $property->formatMoney((float) $property->precio, 'COP') : 'Precio bajo consulta' }}
                @endif
            </div>
            <div class="property-cta-top">
                <a href="{{ $whatsappGeneralLink }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger property-whatsapp-btn property-whatsapp-btn--main">Consultar por WhatsApp</a>
            </div>
            <div class="property-trust-strip">
                <span>Respuesta rápida por WhatsApp</span>
                <span>Asesoría sin costo</span>
            </div>
        </div>

        <div class="property-body">
            <div class="property-gallery-block">
                <div class="gallery-head">
                    <h3>Galería</h3>
                    <p>{!! $property->descripcion_corta ?? e($property->titulo) !!}</p>
                </div>
                @php
                    $galleryItems = $property->galeria ?? [];
                    $galleryCount = count($galleryItems);
                @endphp
                <div id="content-galeria-interna" class="gallery-grid {{ $galleryCount > 3 ? 'gallery-grid--collapsed' : '' }}" data-gallery-grid>
                    @if (!empty($galleryItems))
                        @foreach ($galleryItems as $index => $image)
                            @php
                                $thumb = $property->galeria_thumbs[$index] ?? null;
                                $src = $thumb ? Storage::url($thumb) : Storage::url($image);
                                $full = Storage::url($image);
                            @endphp
                            <div class="gallery-item {{ $index === 0 ? 'gallery-main' : 'gallery-thumb' }}" data-full="{{ $full }}">
                                <img src="{{ $index === 0 ? $full : $src }}" alt="{{ $property->titulo }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            </div>
                        @endforeach
                    @else
                        <p>No hay imágenes adicionales.</p>
                    @endif
                </div>
                @if ($galleryCount > 1)
                    <div class="gallery-mobile-nav" data-gallery-mobile-nav>
                        <button type="button" class="btn btn-outline-dark btn-sm" data-gallery-mobile-prev aria-label="Anterior">‹</button>
                        <span class="gallery-mobile-counter" data-gallery-mobile-counter></span>
                        <button type="button" class="btn btn-outline-dark btn-sm" data-gallery-mobile-next aria-label="Siguiente">›</button>
                    </div>
                @endif
                @if ($galleryCount > 3)
                    <div class="gallery-toggle">
                        <button type="button" class="btn btn-outline-dark btn-sm" data-gallery-toggle aria-expanded="false">
                            Ver más
                        </button>
                    </div>
                @endif
            </div>

            <h1>{!! $property->descripcion_corta ?? e($property->titulo) !!}</h1>
            @if ($property->descripcion)
                {!! $property->descripcion !!}
            @else
                <p>Información en construcción.</p>
            @endif

            <div class="property-quick-actions">
                <a href="{{ $whatsappLocationLink }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger">Solicitar ubicación</a>
                <a href="{{ $whatsappSimilarLink }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger">Ver opciones similares</a>
            </div>

            <div class="content-items-interna dos-items property-features">
                @if ($property->for_sale || $property->for_rent)
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>
                            {{ $property->for_sale ? 'Venta' : '' }}
                            {{ $property->for_sale && $property->for_rent ? ' y ' : '' }}
                            {{ $property->for_rent ? 'Arriendo' : '' }}
                        </p>
                    </span>
                @endif
                @if ($property->for_rent && !is_null($property->administracion_incluida))
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>Administración {{ $property->administracion_incluida ? 'incluida' : 'no incluida' }}</p>
                    </span>
                @endif
                @if ($property->property_type)
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>{{ $property->property_type }}</p>
                    </span>
                @endif
                @if ($property->estrato)
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>Estrato {{ $property->estrato }}</p>
                    </span>
                @endif
                @if ($property->habitaciones)
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>{{ $property->habitaciones }} habitaciones</p>
                    </span>
                @endif
                @if ($property->banos)
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>{{ $property->banos }} baños</p>
                    </span>
                @endif
                @if ($property->area_m2)
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>{{ number_format((float) $property->area_m2, 1, ',', '.') }} m²</p>
                    </span>
                @endif
                @if (!is_null($property->tiene_parqueadero))
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>Parqueadero {{ $property->tiene_parqueadero ? 'sí' : 'no' }}</p>
                    </span>
                @endif
                @if (!is_null($property->tiene_bodega))
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>Bodega {{ $property->tiene_bodega ? 'sí' : 'no' }}</p>
                    </span>
                @endif
                @if ($property->barrio)
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>Barrio {{ $property->barrio }}</p>
                    </span>
                @endif
                @if ($property->is_conjunto && $property->conjunto_nombre)
                    <span>
                        <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                            <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                        </svg>
                        <p>Conjunto {{ $property->conjunto_nombre }}</p>
                    </span>
                @endif
            </div>

            @php
                $youtubeId = null;
                if ($property->youtube_url) {
                    $url = $property->youtube_url;
                    if (str_contains($url, 'youtu.be/')) {
                        $youtubeId = trim(parse_url($url, PHP_URL_PATH), '/');
                    } else {
                        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $params);
                        $youtubeId = $params['v'] ?? null;
                    }
                }
            @endphp

            @if ($youtubeId)
                <h3 class="mt-5">Video de la propiedad</h3>
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" title="Video de {{ $property->titulo }}" allowfullscreen></iframe>
                </div>
            @endif
        </div>
    </div>

    <aside class="property-aside">
        <div class="contact-card">
            <h3>Habla con un asesor</h3>
            <p>Resolvemos disponibilidad, ubicación y opciones similares por WhatsApp.</p>
            <div class="contact-trust-box">
                <strong>Te respondemos por WhatsApp en minutos</strong>
                <span>Recibe disponibilidad, ubicación y condiciones del inmueble.</span>
            </div>
            <a href="{{ $whatsappGeneralLink }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger property-whatsapp-btn w-100 mb-2">
                Consultar por WhatsApp
            </a>
            <div class="contact-quick-links">
                <a href="{{ $whatsappVisitLink }}" target="_blank" rel="noopener noreferrer">Agendar visita</a>
                <a href="{{ $whatsappLocationLink }}" target="_blank" rel="noopener noreferrer">Pedir ubicación</a>
            </div>
        </div>
    </aside>
</section>

<div class="lightbox" id="propertyLightbox" aria-hidden="true">
    <button class="lightbox-nav lightbox-prev" type="button" aria-label="Anterior">‹</button>
    <button class="lightbox-close" type="button" aria-label="Cerrar">×</button>
    <button class="lightbox-nav lightbox-next" type="button" aria-label="Siguiente">›</button>
    <div class="lightbox-counter"></div>
    <img src="" alt="{{ $property->titulo }}">
</div>

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const galleryGrid = document.querySelector('[data-gallery-grid]');
    const galleryToggle = document.querySelector('[data-gallery-toggle]');

    const getGalleryItems = () => Array.from(document.querySelectorAll('#content-galeria-interna .gallery-item[data-full]'));
    const mobileNav = document.querySelector('[data-gallery-mobile-nav]');
    const mobilePrev = document.querySelector('[data-gallery-mobile-prev]');
    const mobileNext = document.querySelector('[data-gallery-mobile-next]');
    const mobileCounter = document.querySelector('[data-gallery-mobile-counter]');
    const mobileQuery = window.matchMedia('(max-width: 900px)');
    let mobileIndex = 0;
    let currentIndex = 0;
    const lightbox = document.getElementById('propertyLightbox');
    const lightboxImg = lightbox?.querySelector('img');
    const closeBtn = lightbox?.querySelector('.lightbox-close');
    const prevBtn = lightbox?.querySelector('.lightbox-prev');
    const nextBtn = lightbox?.querySelector('.lightbox-next');
    const counter = lightbox?.querySelector('.lightbox-counter');

    const openLightbox = (index) => {
        if (!lightbox || !lightboxImg) return;
        const galleryItems = getGalleryItems();
        currentIndex = index;
        lightboxImg.src = galleryItems[currentIndex]?.dataset.full || '';
        if (counter) counter.textContent = `${currentIndex + 1} / ${galleryItems.length}`;
        lightbox.classList.add('is-open');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };

    const closeLightbox = () => {
        if (!lightbox || !lightboxImg) return;
        lightbox.classList.remove('is-open');
        lightbox.setAttribute('aria-hidden', 'true');
        lightboxImg.src = '';
        document.body.style.overflow = '';
    };

    const showNext = (direction) => {
        if (!lightbox || !lightboxImg) return;
        const galleryItems = getGalleryItems();
        if (!galleryItems.length) return;
        const total = galleryItems.length;
        currentIndex = (currentIndex + direction + total) % total;
        lightboxImg.src = galleryItems[currentIndex].dataset.full;
        if (counter) counter.textContent = `${currentIndex + 1} / ${total}`;
    };

    document.getElementById('content-galeria-interna')?.addEventListener('click', (event) => {
        const item = event.target.closest('.gallery-item[data-full]');
        if (!item) return;
        const galleryItems = getGalleryItems();
        const index = galleryItems.indexOf(item);
        if (index >= 0) openLightbox(index);
    });

    const updateMobileGallery = () => {
        if (!galleryGrid) return;
        const galleryItems = getGalleryItems();
        if (!galleryItems.length) return;
        galleryItems.forEach((item, index) => {
            item.classList.toggle('is-active', index === mobileIndex);
        });
        if (mobileCounter) {
            mobileCounter.textContent = `${mobileIndex + 1} / ${galleryItems.length}`;
        }
    };

    const applyMobileMode = () => {
        if (!galleryGrid) return;
        const isMobile = mobileQuery.matches;
        const toggle = galleryToggle?.parentElement;
        galleryGrid.classList.toggle('gallery-grid--mobile', isMobile);
        if (mobileNav) mobileNav.style.display = isMobile ? 'flex' : 'none';
        if (toggle) toggle.style.display = isMobile ? 'none' : '';
        if (isMobile) {
            updateMobileGallery();
        }
    };

    mobilePrev?.addEventListener('click', () => {
        const galleryItems = getGalleryItems();
        if (!galleryItems.length) return;
        mobileIndex = (mobileIndex - 1 + galleryItems.length) % galleryItems.length;
        updateMobileGallery();
    });

    mobileNext?.addEventListener('click', () => {
        const galleryItems = getGalleryItems();
        if (!galleryItems.length) return;
        mobileIndex = (mobileIndex + 1) % galleryItems.length;
        updateMobileGallery();
    });

    mobileQuery.addEventListener('change', applyMobileMode);

    galleryToggle?.addEventListener('click', () => {
        if (!galleryGrid) return;
        const isCollapsed = galleryGrid.classList.toggle('gallery-grid--collapsed');
        galleryToggle.textContent = isCollapsed ? 'Ver más' : 'Ver menos';
        galleryToggle.setAttribute('aria-expanded', (!isCollapsed).toString());
    });

    applyMobileMode();

    lightbox?.addEventListener('click', (event) => {
        if (event.target === lightbox || event.target === closeBtn) {
            closeLightbox();
        }
    });

    prevBtn?.addEventListener('click', (event) => {
        event.stopPropagation();
        showNext(-1);
    });

    nextBtn?.addEventListener('click', (event) => {
        event.stopPropagation();
        showNext(1);
    });

    lightboxImg.addEventListener('click', (event) => {
        event.stopPropagation();
        showNext(1);
    });

    document.addEventListener('keydown', (event) => {
        if (!lightbox || !lightbox.classList.contains('is-open')) return;
        if (event.key === 'Escape') {
            closeLightbox();
        }
        if (event.key === 'ArrowLeft') {
            showNext(-1);
        }
        if (event.key === 'ArrowRight') {
            showNext(1);
        }
    });
});
</script>
@endpush

@endsection
