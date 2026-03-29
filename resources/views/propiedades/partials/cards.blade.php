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
        <div class="card shadow-sm h-100">
            <div class="position-relative">
                <img src="{{ $image }}" class="card-img-top" alt="{{ $propiedad->titulo }}">
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
                        Venta: ${{ number_format($propiedad->sale_price, 0, ',', '.') }}
                    @endif
                    @if ($propiedad->for_rent && $propiedad->rent_price)
                        <span class="d-block">Arriendo: ${{ number_format($propiedad->rent_price, 0, ',', '.') }}</span>
                    @endif
                    @if (! $propiedad->for_sale && ! $propiedad->for_rent)
                        {{ $propiedad->precio ? '$'.number_format($propiedad->precio, 0, ',', '.') : 'Precio bajo consulta' }}
                    @endif
                </p>
                <a href="{{ route('propiedades.show', $propiedad->slug) }}" class="btn btn-outline-danger w-100">Ver más</a>
            </div>
        </div>
    </div>
@endforeach
