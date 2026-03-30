@extends('layouts.app')

@section('title', 'Página no encontrada | Raíces de la Sabana')
@section('meta_description', 'La página que buscas no está disponible. Te llevamos al listado de propiedades para que sigas explorando inmuebles.')
@section('meta_robots', 'noindex,follow')
@section('whatsapp_title', '¿Necesitas ayuda para encontrar un inmueble?')
@section('whatsapp_subtitle', 'Escríbenos y te ayudamos a llegar a propiedades disponibles en la Sabana de Bogotá.')

@push('page_styles')
<style>
    .error-404-wrap {
        min-height: calc(100vh - 220px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 56px 20px;
        background:
            radial-gradient(circle at top left, rgba(201, 109, 58, 0.10), transparent 28%),
            linear-gradient(180deg, #f9f6f1 0%, #ffffff 62%);
    }

    .error-404-card {
        width: min(760px, 100%);
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(249,246,241,0.96));
        border: 1px solid rgba(47, 34, 27, 0.08);
        border-radius: 28px;
        box-shadow: 0 22px 48px rgba(31, 26, 22, 0.10);
        padding: 34px 30px;
        text-align: center;
    }

    .error-404-logo {
        width: 110px;
        height: auto;
        margin-bottom: 18px;
    }

    .error-404-code {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        border-radius: 999px;
        background: rgba(201, 109, 58, 0.12);
        color: #7a432d;
        font-weight: 700;
        letter-spacing: 0.4px;
        margin-bottom: 18px;
    }

    .error-404-card h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        color: #2f221b;
        margin-bottom: 12px;
    }

    .error-404-card p {
        color: #5b514a;
        font-size: 1.02rem;
        line-height: 1.7;
        margin-bottom: 12px;
    }

    .error-404-timer {
        font-weight: 700;
        color: #6b3d2a;
    }

    .error-404-actions {
        margin-top: 22px;
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .error-404-actions .btn {
        min-height: 48px;
        border-radius: 999px;
        padding-inline: 22px;
        font-weight: 700;
    }

    @media (max-width: 576px) {
        .error-404-wrap {
            padding: 30px 16px;
        }

        .error-404-card {
            border-radius: 20px;
            padding: 26px 20px;
        }

        .error-404-actions {
            flex-direction: column;
        }

        .error-404-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
@php
    $redirectUrl = route('propiedades.index');
@endphp
<section class="error-404-wrap">
    <div class="error-404-card">
        <img src="{{ asset('img/logo.png') }}" alt="Raíces de la Sabana" class="error-404-logo">
        <div class="error-404-code">Error 404</div>
        <h1>Esta página no está disponible</h1>
        <p>La ruta que intentaste abrir no existe o fue movida. Te llevaremos automáticamente al listado de propiedades para que sigas explorando inmuebles disponibles.</p>
        <p class="error-404-timer">Redirigiendo en <span id="redirectCountdown">5</span> segundos...</p>
        <div class="error-404-actions">
            <a href="{{ $redirectUrl }}" class="btn btn-danger">Ir a Propiedades</a>
            <a href="{{ url('/') }}" class="btn btn-outline-danger">Volver al inicio</a>
        </div>
    </div>
</section>
@endsection

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const redirectUrl = @json($redirectUrl);
    const countdownElement = document.getElementById('redirectCountdown');
    let seconds = 5;

    const interval = window.setInterval(() => {
        seconds -= 1;

        if (countdownElement) {
            countdownElement.textContent = String(Math.max(seconds, 0));
        }

        if (seconds <= 0) {
            window.clearInterval(interval);
            window.location.href = redirectUrl;
        }
    }, 1000);
});
</script>
@endpush
