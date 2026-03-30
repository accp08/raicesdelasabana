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
    @php
        $cardWhatsappMessage = "Hola, quiero información sobre el inmueble {$propiedad->titulo} en ".($propiedad->city?->name ?? $propiedad->ciudad).". Link: ".route('propiedades.show', $propiedad->slug);
        $cardWhatsappHref = 'https://wa.me/573150597595?text=' . rawurlencode($cardWhatsappMessage);
    @endphp
    <div class="col-md-4">
        <div class="card shadow-sm h-100 property-grid-card">
            <div class="position-relative">
                <img src="{{ $image }}" class="card-img-top" alt="{{ $propiedad->titulo }}" width="640" height="360" loading="lazy" decoding="async">
                <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                @if ($propiedad->is_featured)
                    <span class="badge badge-featured">⭐ Destacada</span>
                @endif
            </div>
            <div class="card-body">
                <h5 class="card-title fw-bold">{{ $propiedad->titulo }}</h5>
                <p class="card-text mb-1">{{ $propiedad->city?->name ?? $propiedad->ciudad }}</p>
                @if ($propiedad->area_m2)
                    <p class="card-text mb-1">Área: {{ number_format((float) $propiedad->area_m2, 1, ',', '.') }} m²</p>
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
                <div class="card-cta-stack">
                    <a href="{{ route('propiedades.show', $propiedad->slug) }}" class="btn btn-outline-danger w-100">Ver más</a>
                    <a href="{{ $cardWhatsappHref }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger w-100 property-whatsapp-btn">WhatsApp</a>
                </div>
            </div>
        </div>
    </div>
@endforeach
