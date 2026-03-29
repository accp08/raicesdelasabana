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
                        <a class="btn btn-outline-danger w-100" href="{{ route('blog.show', $post->slug) }}" class="btn btn-ver-blog">Ver Más</a>
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
