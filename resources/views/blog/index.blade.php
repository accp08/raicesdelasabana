@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp
@push('page_styles')
<link href="{{ asset('css/nosotros.css?id=1') }}" rel="stylesheet" />
<link href="{{ asset('css/blog.css?id=1') }}" rel="stylesheet" />
@endpush 

@php
    $selectedCategory = $categories->firstWhere('id', (int) request('category'));
    $blogPaginated = $posts->currentPage() > 1;
    $blogHasFilters = request()->filled('category');
    $blogCanonical = $blogPaginated ? $posts->url($posts->currentPage()) : route('blog.index');
    $blogTitle = $selectedCategory
        ? 'Blog inmobiliario de '.$selectedCategory->name.' | Raíces de la Sabana'
        : 'Blog inmobiliario | Raíces de la Sabana';
    $blogDescription = $selectedCategory
        ? 'Consejos y guías inmobiliarias sobre '.$selectedCategory->name.' en la Sabana de Bogotá.'
        : 'Consejos, tendencias y guías del mercado inmobiliario en la Sabana de Bogotá. Aprende a comprar, vender o invertir mejor.';
    $blogBreadcrumbs = [
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
                'name' => 'Blog',
                'item' => route('blog.index'),
            ],
        ],
    ];
@endphp

@section('title', $blogTitle)
@section('meta_description', $blogDescription)
@section('canonical', $blogCanonical)
@section('meta_robots', $blogHasFilters ? 'noindex,follow' : 'index,follow')
@section('whatsapp_link', 'https://wa.me/573150597595?text='.rawurlencode('Hola, estoy leyendo el blog de Raíces de la Sabana y quiero hablar con un asesor sobre una compra, arriendo o inversión inmobiliaria.'))
@section('whatsapp_title', 'Resolver mi duda por WhatsApp')
@section('whatsapp_subtitle', 'Si un artículo te interesó, te ayudamos a convertir esa información en una búsqueda o decisión real.')

@push('seo_links')
    @if ($posts->previousPageUrl())
<link rel="prev" href="{{ $posts->previousPageUrl() }}">
    @endif
    @if ($posts->nextPageUrl())
<link rel="next" href="{{ $posts->nextPageUrl() }}">
    @endif
@endpush

@push('structured_data')
<script type="application/ld+json">{!! json_encode($blogBreadcrumbs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')


<div class="container py-4">
    <div class="mb-3">
        <h1 class="h3 fw-bold">Blog inmobiliario</h1>
        <p class="text-muted">Ideas, análisis y recomendaciones para tomar mejores decisiones inmobiliarias.</p>
        <div class="listing-cta-card">
            <div>
                <strong>¿Quieres aterrizar esta información a tu caso?</strong>
                <p>Escríbenos por WhatsApp y te ayudamos a revisar zonas, presupuesto e inmuebles según tu objetivo.</p>
            </div>
            <a href="@yield('whatsapp_link')" target="_blank" rel="noopener noreferrer" class="btn btn-danger">Hablar con un asesor</a>
        </div>
    </div>
    <form class="row g-2 justify-content-end" method="GET">
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">Todas las categorías</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-danger w-100" type="submit">Filtrar</button>
        </div>
    </form>
</div>

<div id="p-content-cards-blog">
    <div id="content-cards-blog">
        @if ($posts->isNotEmpty())
            @foreach ($posts as $post)
                @php
                    $image = $post->cover_image_thumb
                        ? Storage::url($post->cover_image_thumb)
                        : ($post->cover_image ? Storage::url($post->cover_image) : asset('img/banner-blog.webp'));
                @endphp
                <div class="card-blog">
                    <img src="{{ $image }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
                    <div class="info-card-blog">
                        <h5>{{ $post->title }}</h5>
                        <p>{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 140) }}</p>
                        @php
                            $blogWhatsappMessage = "Hola, estoy leyendo este artículo: {$post->title}. Quiero orientación sobre inmuebles o inversión. Link: ".route('blog.show', $post->slug);
                            $blogWhatsappHref = 'https://wa.me/573150597595?text=' . rawurlencode($blogWhatsappMessage);
                        @endphp
                        <div class="card-cta-stack">
                            <a class="btn btn-outline-danger w-100" href="{{ route('blog.show', $post->slug) }}">Ver Más</a>
                            <a class="btn btn-danger w-100" href="{{ $blogWhatsappHref }}" target="_blank" rel="noopener noreferrer">Hablar por WhatsApp</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning text-center w-100">
                No hay publicaciones para mostrar.
            </div>
        @endif
    </div>
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>

@endsection
