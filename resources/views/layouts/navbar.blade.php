<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm front-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Raíces de la Sabana" class="logo-central">
        </a>

        {{-- Botón hamburguesa móvil --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir menú principal">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menú colapsable --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a @class(['nav-link', 'active' => request()->is('/')]) href="{{ url('/') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a @class(['nav-link', 'active' => request()->is('propiedades*')]) href="{{ url('/propiedades') }}">Propiedades</a>
                </li>
                <li class="nav-item">
                    <a @class(['nav-link', 'active' => request()->is('blog*')]) href="{{ url('/blog') }}">Blog</a>
                </li>
                <li class="nav-item">
                    <a @class(['nav-link', 'active' => request()->is('nosotros')]) href="{{ url('/nosotros') }}">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#footer">Contáctanos</a>
                </li>
            </ul>

            <div class="d-none d-lg-flex align-items-center navbar-actions">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSedXes7OzbtoVm81sHZifiyQNEAyTKquf4jW41lTvvHj3Cvjw/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="btn btn-danger fw-bold shadow-sm navbar-consign">
                    Consigna tu inmueble
                </a>
                <a href="tel:+573150597595" class="btn btn-outline-danger fw-bold shadow-sm navbar-phone">
                    📞 +57 315 059 7595
                </a>
            </div>

            {{-- Teléfono visible en móvil --}}
            <div class="d-lg-none text-center mt-3 w-100">
                <a href="https://docs.google.com/forms/d/e/1FAIpQLSedXes7OzbtoVm81sHZifiyQNEAyTKquf4jW41lTvvHj3Cvjw/viewform?usp=publish-editor" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger fw-bold w-100 mb-2">
                    Consigna tu inmueble
                </a>
                <a href="tel:+573150597595" class="btn btn-danger fw-bold w-100">
                    Llamar al +57 315 059 7595
                </a>
            </div>
        </div>
    </div>
</nav>
