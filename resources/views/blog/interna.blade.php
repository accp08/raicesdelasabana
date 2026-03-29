@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp
@push('page_styles')
<link href="{{ asset('css/blog.css?id=1') }}" rel="stylesheet" />
@endpush 

@section('title', ($post->seo_title ?? $post->title).' | Raíces de la Sabana')
@section('meta_description', $post->seo_description ?? $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@section('canonical', route('blog.show', $post->slug))
@section('meta_og_type', 'article')
@section('meta_og_image', $post->cover_image ? Storage::url($post->cover_image) : asset('img/banner-blog.webp'))
@section('meta_twitter_image', $post->cover_image ? Storage::url($post->cover_image) : asset('img/banner-blog.webp'))

@push('structured_data')
@php
    $postImage = $post->cover_image ? Storage::url($post->cover_image) : asset('img/banner-blog.webp');
    $postBreadcrumbs = [
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
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $post->title,
                'item' => route('blog.show', $post->slug),
            ],
        ],
    ];
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->seo_title ?: $post->title,
        'description' => strip_tags($post->seo_description ?: $post->excerpt ?: Str::limit(strip_tags($post->content), 160)),
        'image' => [$postImage],
        'datePublished' => optional($post->published_at ?: $post->created_at)->toAtomString(),
        'dateModified' => optional($post->updated_at ?: $post->published_at ?: $post->created_at)->toAtomString(),
        'mainEntityOfPage' => route('blog.show', $post->slug),
        'author' => [
            '@type' => 'Organization',
            'name' => 'Raíces de la Sabana',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Raíces de la Sabana',
            'url' => config('app.url'),
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($postBreadcrumbs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')    
<section id="s-blog-interna">
    <div id="content-blog-interna">
        @php
            $image = $post->cover_image ? Storage::url($post->cover_image) : asset('img/banner-blog.webp');
        @endphp
        <img src="{{ $image }}" alt="{{ $post->title }}">

        <div id="content-info-blog-interna">
            <h1>{{ $post->title }}</h1>
            @if ($post->excerpt)
                <div class="content-blog-excerpt">
                    {!! $post->excerpt !!}
                </div>
            @endif
            <div class="content-blog-body">
                {!! $post->content !!}
            </div>
        </div>
    </div>
</section>

@endsection
