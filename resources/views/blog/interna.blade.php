@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp
@push('page_styles')
<link href="{{ asset('css/blog.css?id=1') }}" rel="stylesheet" />
@endpush 

@section('title', ($post->seo_title ?? $post->title).' | Raíces de la Sabana')
@section('meta_description', $post->seo_description ?? $post->excerpt ?? Str::limit(strip_tags($post->content), 160))
@section('meta_og_type', 'article')
@section('meta_og_image', $post->cover_image ? Storage::url($post->cover_image) : asset('img/banner-blog.webp'))
@section('meta_twitter_image', $post->cover_image ? Storage::url($post->cover_image) : asset('img/banner-blog.webp'))

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
