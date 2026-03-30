@extends('layouts.app')
@push('page_styles')
<link href="{{ asset('css/nosotros.css?id=2') }}" rel="stylesheet" />
@endpush 

@php
    $heroImage = $about?->hero_image_thumb
        ? Storage::url($about->hero_image_thumb)
        : ($about?->hero_image ? Storage::url($about->hero_image) : url('/img/banner-nosotros.webp'));
@endphp

@section('title', 'Nosotros | Raíces de la Sabana')
@section('meta_description', 'Conoce a Raíces de la Sabana: experiencia inmobiliaria, acompañamiento experto y servicios integrales en la Sabana de Bogotá.')
@section('meta_og_image', $heroImage)
@section('meta_twitter_image', $heroImage)
@section('whatsapp_link', 'https://wa.me/573150597595?text='.rawurlencode('Hola, quiero conocer mejor el servicio de Raíces de la Sabana y hablar con un asesor.'))
@section('whatsapp_title', 'Hablar con Raíces de la Sabana por WhatsApp')
@section('whatsapp_subtitle', 'Te contamos cómo trabajamos y te ayudamos a encontrar o consignar tu inmueble con acompañamiento directo.')

@section('content')


<section id="s-nosotros">
    <div class="nosotros-intro">
        <h1>Nosotros</h1>
        <p>Conoce el equipo y los valores que impulsan cada experiencia inmobiliaria.</p>
    </div>
    <div class="card-nosotros reveal">
        <img src="{{ $about?->section1_image_thumb ? Storage::url($about->section1_image_thumb) : ($about?->section1_image ? Storage::url($about->section1_image) : url('/img/quienes-somos.webp')) }}" alt="{{ $about?->section1_title ?? 'Equipo de Raíces de la Sabana' }}" loading="lazy" decoding="async">
        <div class="info-nosotros">
            <h3>{{ $about?->section1_title ?? '¿Quiénes somos?' }}</h3>
            {!! nl2br(e($about?->section1_body ?? '')) !!}
        </div>
    </div>

    <div class="card-nosotros reveal">
        <img src="{{ $about?->section2_image_thumb ? Storage::url($about->section2_image_thumb) : ($about?->section2_image ? Storage::url($about->section2_image) : url('/img/lineas-de-negocio.webp')) }}" alt="{{ $about?->section2_title ?? 'Líneas de negocio inmobiliario' }}" loading="lazy" decoding="async">
        <div class="info-nosotros">
            <h3>{{ $about?->section2_title ?? 'Líneas de negocio' }}</h3>
            {!! nl2br(e($about?->section2_body ?? '')) !!}

            @if (!empty($about?->section2_items))
                <div class="content-items-interna dos-items">
                    @foreach ($about->section2_items as $item)
                        <span>
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                            </svg>
                            <p>{{ $item }}</p>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="card-nosotros reveal">
        <img src="{{ $about?->section3_image_thumb ? Storage::url($about->section3_image_thumb) : ($about?->section3_image ? Storage::url($about->section3_image) : url('/img/por-que-elegirnos.webp')) }}" alt="{{ $about?->section3_title ?? 'Razones para elegir Raíces de la Sabana' }}" loading="lazy" decoding="async">
        <div class="info-nosotros">
            <h3>{{ $about?->section3_title ?? '¿Por qué elegirnos?' }}</h3>
            {!! nl2br(e($about?->section3_body ?? '')) !!}

            @if (!empty($about?->section3_items))
                <div class="content-items-interna dos-items">
                    @foreach ($about->section3_items as $item)
                        <span>
                            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                <path fill="#fff" d="M12 2C6.5 2 2 6.5 2 12S6.5 22 12 22 22 17.5 22 12 17.5 2 12 2M10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z"></path>
                            </svg>
                            <p>{{ $item }}</p>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="card-nosotros reveal">
        <img src="{{ $about?->section4_image_thumb ? Storage::url($about->section4_image_thumb) : ($about?->section4_image ? Storage::url($about->section4_image) : url('/img/lo-confirman.webp')) }}" alt="{{ $about?->section4_title ?? 'Testimonios de clientes' }}" loading="lazy" decoding="async">
        <div class="info-nosotros">
            <h3>{{ $about?->section4_title ?? 'Nuestros clientes lo confirman' }}</h3>
            {!! nl2br(e($about?->section4_body ?? '')) !!}
        </div>
    </div>

    <div class="card-nosotros reveal">
        <img src="{{ $about?->section5_image_thumb ? Storage::url($about->section5_image_thumb) : ($about?->section5_image ? Storage::url($about->section5_image) : url('/img/contactanos.webp')) }}" alt="{{ $about?->section5_title ?? 'Contacto inmobiliario' }}" loading="lazy" decoding="async">
        <div class="info-nosotros">
            <h3>{{ $about?->section5_title ?? 'Contáctanos' }}</h3>
            {!! nl2br(e($about?->section5_body ?? '')) !!}

            @if ($about?->contact_name)
                <h4>{{ $about->contact_name }}</h4>
            @endif
            @if ($about?->contact_role)
                <h5>{{ $about->contact_role }}</h5>
            @endif
            @if ($about?->contact_phone)
                <a class="btn-contacto-nosotros" href="tel:{{ $about->contact_phone }}"><b>Teléfono:</b> {{ $about->contact_phone }}</a>
            @endif
            @if ($about?->contact_email)
                <a class="btn-contacto-nosotros" href="mailto:{{ $about->contact_email }}"><b>Email:</b> {{ $about->contact_email }}</a>
            @endif
            <div class="card-cta-stack mt-3">
                <a href="@yield('whatsapp_link')" target="_blank" rel="noopener noreferrer" class="btn btn-danger">Escribir por WhatsApp</a>
                <a href="{{ route('propiedades.index') }}" class="btn btn-outline-danger">Ver inmuebles disponibles</a>
            </div>
        </div>
    </div>
</section>

@endsection
