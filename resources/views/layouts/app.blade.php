<!DOCTYPE html>
<html lang="es">
<head>
    @php
        $defaultTitle = 'Raíces de la Sabana | Inmobiliaria en la Sabana de Bogotá';
        $defaultDescription = 'Compra, venta y arriendo de propiedades en la Sabana de Bogotá. Asesoría experta y acompañamiento integral.';
        $pageTitle = trim($__env->yieldContent('title')) ?: $defaultTitle;
        $pageDescription = trim($__env->yieldContent('meta_description')) ?: $defaultDescription;
        $canonicalUrl = trim($__env->yieldContent('canonical')) ?: url()->current();
        $robotsContent = trim($__env->yieldContent('meta_robots')) ?: 'index,follow';
        $ogTitle = trim($__env->yieldContent('meta_og_title')) ?: $pageTitle;
        $ogDescription = trim($__env->yieldContent('meta_og_description')) ?: $pageDescription;
        $twitterTitle = trim($__env->yieldContent('meta_twitter_title')) ?: $pageTitle;
        $twitterDescription = trim($__env->yieldContent('meta_twitter_description')) ?: $pageDescription;
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robotsContent }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:type" content="@yield('meta_og_type', 'website')">
    <meta property="og:locale" content="es_CO">
    <meta property="og:site_name" content="Raíces de la Sabana">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="@yield('meta_og_image', asset('img/fondo-campo.jpg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $twitterTitle }}">
    <meta name="twitter:description" content="{{ $twitterDescription }}">
    <meta name="twitter:image" content="@yield('meta_twitter_image', asset('img/fondo-campo.jpg'))">
    @stack('seo_links')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('page_styles')
    @stack('structured_data')
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-C958Y2Y4DK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-C958Y2Y4DK');
</script>

</head>
<body class="site">
    @include('layouts.navbar')

    <main class="py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('page_scripts')
    @php
        $defaultWhatsappMessage = 'Hola, quiero información sobre inmuebles disponibles en la Sabana de Bogotá.';
        $whatsappHref = trim($__env->yieldContent('whatsapp_link')) ?: 'https://wa.me/573150597595?text='.rawurlencode($defaultWhatsappMessage);
        $whatsappTitle = trim($__env->yieldContent('whatsapp_title')) ?: 'Escríbenos por WhatsApp';
    @endphp
    <!-- Botón flotante de WhatsApp -->
<a href="{{ $whatsappHref }}" class="whatsapp-float" target="_blank" rel="noopener noreferrer" title="{{ $whatsappTitle }}" aria-label="{{ $whatsappTitle }}">
    <img src="{{ asset('img/whatsapp-icon.png') }}" alt="WhatsApp" class="whatsapp-icon">
</a>

<footer class="footer" id="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Sección del logotipo -->
            <div class="footer-section logo-section">
                <img src="{{ asset('img/logo.png') }}" alt="Raíces de la Sabana" class="footer-logo">
            </div>
            <!-- Información de contacto -->
            <div class="footer-section">
                <h5>Contacto</h5>
                <p>Teléfono: +57 3150597595</p>
                <p>Email: contacto@raicesdelasabana.com</p>
               
            </div>
            <!-- Enlaces rápidos -->
            <div class="footer-section">
                <h5>Enlaces Rápidos</h5>
                <ul>
                    <li><a href="{{ url('/') }}">Inicio</a></li>
                    <li><a href="{{ url('/propiedades') }}">Propiedades</a></li>
                    <li><a href="{{ url('/nosotros') }}">Nosotros</a></li>
                    <li><a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer">WhatsApp</a></li>
                    <li><a href="mailto:contacto@raicesdelasabana.com">Contáctenos</a></li>
                </ul>
            </div>
        </div>
        <!-- Derechos de autor -->
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} Raíces de la Sabana. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

</body>
</html>
