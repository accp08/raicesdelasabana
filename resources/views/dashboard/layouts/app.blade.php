<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Raíces de la Sabana</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body class="dashboard-body">
<div class="dashboard-wrapper">
    <aside class="dashboard-sidebar">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}" class="text-decoration-none">
                <img src="{{ asset('img/logo.png') }}" alt="Raíces de la Sabana">
            </a>
            <span>Dashboard</span>
        </div>
        <button class="dashboard-menu-trigger d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardMenu" aria-expanded="false" aria-controls="dashboardMenu">
            Menú del panel
        </button>
        <div class="dashboard-menu collapse d-lg-block" id="dashboardMenu">
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard.home') }}" class="{{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
                    <span class="menu-icon">🏠</span> Inicio
                </a>
                <button class="sidebar-toggle {{ request()->routeIs('dashboard.properties.*') || request()->routeIs('dashboard.posts.*') || request()->routeIs('dashboard.leads.*') || request()->routeIs('dashboard.about.*') || request()->routeIs('dashboard.analytics.*') ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#webSection" aria-expanded="{{ request()->routeIs('dashboard.properties.*') || request()->routeIs('dashboard.posts.*') || request()->routeIs('dashboard.leads.*') || request()->routeIs('dashboard.about.*') || request()->routeIs('dashboard.analytics.*') ? 'true' : 'false' }}" aria-controls="webSection">
                    <span class="menu-icon">🌐</span> Pagweb
                </button>
                <div class="collapse sidebar-subnav {{ request()->routeIs('dashboard.properties.*') || request()->routeIs('dashboard.posts.*') || request()->routeIs('dashboard.leads.*') || request()->routeIs('dashboard.about.*') || request()->routeIs('dashboard.analytics.*') ? 'show' : '' }}" id="webSection">
                    @can('viewAny', App\Models\Property::class)
                        <a href="{{ route('dashboard.properties.index') }}" class="{{ request()->routeIs('dashboard.properties.*') ? 'active' : '' }}">
                            <span class="menu-icon">🏘️</span> Propiedades
                        </a>
                    @endcan
                    @can('viewAny', App\Models\Post::class)
                        <a href="{{ route('dashboard.posts.index') }}" class="{{ request()->routeIs('dashboard.posts.*') ? 'active' : '' }}">
                            <span class="menu-icon">📰</span> Blog
                        </a>
                    @endcan
                    @can('viewAny', App\Models\PropertyLead::class)
                        <a href="{{ route('dashboard.leads.index') }}" class="{{ request()->routeIs('dashboard.leads.*') ? 'active' : '' }}">
                            <span class="menu-icon">📩</span> Contactos
                        </a>
                    @endcan
                    <a href="{{ route('dashboard.analytics.index') }}" class="{{ request()->routeIs('dashboard.analytics.*') ? 'active' : '' }}">
                        <span class="menu-icon">📊</span> Analítica
                    </a>
                    @can('viewAny', App\Models\AboutPage::class)
                        <a href="{{ route('dashboard.about.edit') }}" class="{{ request()->routeIs('dashboard.about.*') ? 'active' : '' }}">
                            <span class="menu-icon">☎️</span> Contáctenos
                        </a>
                    @endcan
                </div>
                <button class="sidebar-toggle {{ request()->routeIs('dashboard.users.*') || request()->routeIs('dashboard.cities.*') || request()->routeIs('dashboard.categories.*') ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#adminSection" aria-expanded="{{ request()->routeIs('dashboard.users.*') || request()->routeIs('dashboard.cities.*') || request()->routeIs('dashboard.categories.*') ? 'true' : 'false' }}" aria-controls="adminSection">
                    <span class="menu-icon">🛠️</span> Administración
                </button>
                <div class="collapse sidebar-subnav {{ request()->routeIs('dashboard.users.*') || request()->routeIs('dashboard.cities.*') || request()->routeIs('dashboard.categories.*') ? 'show' : '' }}" id="adminSection">
                    @can('viewAny', App\Models\User::class)
                        <a href="{{ route('dashboard.users.index') }}" class="{{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}">
                            <span class="menu-icon">👤</span> Usuarios
                        </a>
                    @endcan
                    @can('viewAny', App\Models\Category::class)
                        <a href="{{ route('dashboard.categories.index') }}" class="{{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
                            <span class="menu-icon">🗂️</span> Categorías
                        </a>
                    @endcan
                    @can('viewAny', App\Models\City::class)
                        <a href="{{ route('dashboard.cities.index') }}" class="{{ request()->routeIs('dashboard.cities.*') ? 'active' : '' }}">
                            <span class="menu-icon">📍</span> Ciudades
                        </a>
                    @endcan
                </div>
            </nav>
        </div>
    </aside>

    <div class="dashboard-main">
        <header class="dashboard-topbar">
            <div class="topbar-title">
                @yield('header')
            </div>
            <div class="topbar-actions">
                <div class="user-info">
                    <span>{{ auth()->user()->name }}</span>
                    <small>{{ auth()->user()->email }}</small>
                </div>
                <form method="POST" action="{{ route('dashboard.logout') }}">
                    @csrf
                    <button class="btn btn-outline-brand" type="submit">Cerrar sesión</button>
                </form>
            </div>
        </header>

        <main class="dashboard-content">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const syncPriceFields = (form) => {
        const saleToggle = form.querySelector('input[name="for_sale"]');
        const rentToggle = form.querySelector('input[name="for_rent"]');
        const salePrice = form.querySelector('input[name="sale_price"]');
        const rentPrice = form.querySelector('input[name="rent_price"]');
        const saleCurrency = form.querySelector('select[name="sale_currency"]');
        const rentCurrency = form.querySelector('select[name="rent_currency"]');
        const rentOnlyBlocks = form.querySelectorAll('[data-rent-only]');
        if (!saleToggle || !rentToggle || !salePrice || !rentPrice) return;

        const update = () => {
            salePrice.disabled = !saleToggle.checked;
            rentPrice.disabled = !rentToggle.checked;
            if (saleCurrency) saleCurrency.disabled = !saleToggle.checked;
            if (rentCurrency) rentCurrency.disabled = !rentToggle.checked;
            rentOnlyBlocks.forEach((block) => {
                block.style.display = rentToggle.checked ? '' : 'none';
                block.querySelectorAll('input, select, textarea').forEach((input) => {
                    input.disabled = !rentToggle.checked;
                });
            });
        };

        saleToggle.addEventListener('change', update);
        rentToggle.addEventListener('change', update);
        update();
    };

    document.querySelectorAll('textarea.wysiwyg').forEach((textarea) => {
        const toolbar = document.createElement('div');
        toolbar.className = 'wysiwyg-toolbar';
        toolbar.innerHTML = `
            <button type="button" data-cmd="bold"><strong>B</strong></button>
            <button type="button" data-cmd="italic"><em>I</em></button>
            <button type="button" data-cmd="underline"><u>U</u></button>
            <button type="button" data-cmd="insertUnorderedList">• Lista</button>
            <button type="button" data-cmd="createLink">Link</button>
            <button type="button" data-cmd="toggleHtml">HTML</button>
        `;

        const editor = document.createElement('div');
        editor.className = 'wysiwyg-editor';
        editor.contentEditable = 'true';
        editor.innerHTML = textarea.value || '';

        let isHtmlMode = false;

        toolbar.addEventListener('click', (event) => {
            const button = event.target.closest('button');
            if (!button) return;
            const cmd = button.dataset.cmd;
            if (cmd === 'toggleHtml') {
                const toggleButton = toolbar.querySelector('[data-cmd="toggleHtml"]');
                isHtmlMode = !isHtmlMode;
                if (isHtmlMode) {
                    textarea.value = editor.innerHTML;
                    textarea.style.display = '';
                    editor.style.display = 'none';
                    toggleButton?.classList.add('is-active');
                } else {
                    editor.innerHTML = textarea.value || '';
                    textarea.style.display = 'none';
                    editor.style.display = '';
                    toggleButton?.classList.remove('is-active');
                }
                return;
            }
            if (isHtmlMode) return;
            if (cmd === 'createLink') {
                const url = prompt('URL');
                if (url) document.execCommand(cmd, false, url);
                return;
            }
            document.execCommand(cmd, false, null);
        });

        editor.addEventListener('input', () => {
            textarea.value = editor.innerHTML;
        });

        textarea.addEventListener('input', () => {
            if (isHtmlMode) return;
            editor.innerHTML = textarea.value || '';
        });

        textarea.style.display = 'none';
        textarea.parentNode.insertBefore(toolbar, textarea);
        textarea.parentNode.insertBefore(editor, textarea);

        textarea.closest('form')?.addEventListener('submit', () => {
            textarea.value = isHtmlMode ? textarea.value : editor.innerHTML;
        });
    });

    document.querySelectorAll('form').forEach(syncPriceFields);

    document.querySelectorAll('input[type="file"][name="galeria[]"]').forEach((input) => {
        const wrapper = input.closest('[data-gallery-upload]');
        if (!wrapper) return;

        const info = wrapper.querySelector('[data-gallery-info]');
        const preview = wrapper.querySelector('[data-gallery-preview]');
        const actions = wrapper.querySelector('[data-gallery-actions]');
        const clearBtn = wrapper.querySelector('[data-gallery-clear]');
        const browseBtn = wrapper.querySelector('[data-gallery-browse]');
        const dropzone = wrapper.querySelector('[data-gallery-dropzone]');
        let files = [];
        let dragIndex = null;

        const fileKey = (file) => `${file.name}-${file.size}-${file.lastModified}`;

        const syncInputFiles = () => {
            const dt = new DataTransfer();
            files.forEach((file) => dt.items.add(file));
            input.files = dt.files;
        };

        const render = () => {
            if (!info || !preview || !actions) return;
            info.innerHTML = '';
            preview.innerHTML = '';

            if (!files.length) {
                info.innerHTML = '<div class="file-summary">Sin imágenes seleccionadas.</div>';
                actions.classList.add('d-none');
                return;
            }

            const totalSizeMb = files.reduce((acc, file) => acc + file.size, 0) / (1024 * 1024);
            info.innerHTML = `
                <div class="file-summary">Imágenes: ${files.length} · ${totalSizeMb.toFixed(1)} MB</div>
            `;

            files.forEach((file, index) => {
                const item = document.createElement('div');
                item.className = 'gallery-file-item';
                item.draggable = true;
                item.dataset.index = String(index);
                item.innerHTML = `
                    <img src="${URL.createObjectURL(file)}" alt="${file.name}">
                    <div class="gallery-file-meta">
                        <span class="file-name" title="${file.name}">${file.name}</span>
                        <button type="button" class="btn btn-outline-brand btn-sm" data-remove-index="${index}">Quitar</button>
                    </div>
                `;
                preview.appendChild(item);
            });

            actions.classList.remove('d-none');
        };

        const addFiles = (incoming) => {
            const existing = new Set(files.map(fileKey));
            Array.from(incoming).forEach((file) => {
                if (!existing.has(fileKey(file))) {
                    files.push(file);
                }
            });
            syncInputFiles();
            render();
        };

        input.addEventListener('change', () => {
            if (input.files.length) {
                addFiles(input.files);
            }
        });

        preview?.addEventListener('click', (event) => {
            const button = event.target.closest('[data-remove-index]');
            if (!button) return;
            const index = Number(button.dataset.removeIndex);
            files.splice(index, 1);
            syncInputFiles();
            render();
        });

        preview?.addEventListener('dragstart', (event) => {
            const item = event.target.closest('.gallery-file-item');
            if (!item) return;
            dragIndex = Number(item.dataset.index);
            item.classList.add('is-dragging');
        });

        preview?.addEventListener('dragend', (event) => {
            const item = event.target.closest('.gallery-file-item');
            if (!item) return;
            item.classList.remove('is-dragging');
            dragIndex = null;
        });

        preview?.addEventListener('dragover', (event) => {
            event.preventDefault();
            const item = event.target.closest('.gallery-file-item');
            if (!item || dragIndex === null) return;
            const targetIndex = Number(item.dataset.index);
            if (Number.isNaN(targetIndex) || targetIndex === dragIndex) return;
            const [moved] = files.splice(dragIndex, 1);
            files.splice(targetIndex, 0, moved);
            syncInputFiles();
            render();
        });

        clearBtn?.addEventListener('click', () => {
            files = [];
            syncInputFiles();
            render();
        });

        browseBtn?.addEventListener('click', () => {
            input.click();
        });

        dropzone?.addEventListener('click', (event) => {
            if (event.target.closest('button')) return;
            input.click();
        });

        dropzone?.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropzone.classList.add('is-dragging');
        });

        dropzone?.addEventListener('dragleave', () => {
            dropzone.classList.remove('is-dragging');
        });

        dropzone?.addEventListener('drop', (event) => {
            event.preventDefault();
            dropzone.classList.remove('is-dragging');
            if (event.dataTransfer?.files?.length) {
                addFiles(event.dataTransfer.files);
            }
        });

        render();
    });

    document.querySelectorAll('[data-gallery-existing]').forEach((container) => {
        const checkboxes = Array.from(container.querySelectorAll('input[type="checkbox"][name="remove_gallery[]"]'));
        const summary = container.querySelector('[data-gallery-existing-summary]');
        const selectAllBtn = container.querySelector('[data-gallery-select-all]');
        const clearBtn = container.querySelector('[data-gallery-clear-selection]');
        const clearGalleryCheckbox = document.getElementById('clear_gallery');
        const orderInput = container.querySelector('[data-gallery-order]');
        const galleryPreview = container.querySelector('.gallery-preview');
        let draggingItem = null;

        const updateSummary = () => {
            if (!summary) return;
            const selected = checkboxes.filter((checkbox) => checkbox.checked).length;
            summary.textContent = selected
                ? `${selected} seleccionadas para quitar.`
                : 'Marca las imágenes que deseas quitar.';
        };

        const updateOrder = () => {
            if (!orderInput || !galleryPreview) return;
            const items = Array.from(galleryPreview.querySelectorAll('[data-gallery-item]'));
            orderInput.value = items.map((item) => item.dataset.galleryPath).join('|');
        };

        const setDisabled = (disabled) => {
            checkboxes.forEach((checkbox) => {
                checkbox.disabled = disabled;
                checkbox.closest('.gallery-item-control')?.classList.toggle('is-disabled', disabled);
            });
        };

        selectAllBtn?.addEventListener('click', () => {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = true;
            });
            updateSummary();
        });

        clearBtn?.addEventListener('click', () => {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
            updateSummary();
        });

        galleryPreview?.addEventListener('dragstart', (event) => {
            const item = event.target.closest('[data-gallery-item]');
            if (!item || item.classList.contains('is-disabled')) return;
            draggingItem = item;
            item.classList.add('is-dragging');
        });

        galleryPreview?.addEventListener('dragend', (event) => {
            const item = event.target.closest('[data-gallery-item]');
            if (!item) return;
            item.classList.remove('is-dragging');
            draggingItem = null;
            updateOrder();
        });

        galleryPreview?.addEventListener('dragover', (event) => {
            event.preventDefault();
            const target = event.target.closest('[data-gallery-item]');
            if (!target || !draggingItem || target === draggingItem) return;
            const rect = target.getBoundingClientRect();
            const isAfter = event.clientY > rect.top + rect.height / 2;
            galleryPreview.insertBefore(draggingItem, isAfter ? target.nextSibling : target);
        });

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateSummary);
        });

        clearGalleryCheckbox?.addEventListener('change', () => {
            const isClearing = clearGalleryCheckbox.checked;
            if (isClearing) {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });
            }
            setDisabled(isClearing);
            updateSummary();
        });

        updateSummary();
        updateOrder();
    });
});
</script>
</body>
</html>
