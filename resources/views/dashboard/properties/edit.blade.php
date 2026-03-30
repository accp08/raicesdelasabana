@extends('dashboard.layouts.app')

@section('title', 'Editar propiedad')

@section('header')
    <div>
        <h2>Editar propiedad</h2>
        <p class="text-muted">Actualiza la información del inmueble.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.properties.update', $property) }}" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="accordion" id="propertyAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#sectionBasic">
                        Información básica
                    </button>
                </h2>
                <div id="sectionBasic" class="accordion-collapse collapse show" data-bs-parent="#propertyAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Título</label>
                                <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $property->titulo) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug', $property->slug) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción corta</label>
                                <textarea name="descripcion_corta" class="form-control wysiwyg" rows="2">{{ old('descripcion_corta', $property->descripcion_corta) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control wysiwyg" rows="5">{{ old('descripcion', $property->descripcion) }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tipo de inmueble</label>
                                <select name="property_type" class="form-select">
                                    <option value="">Seleccionar</option>
                                    <option value="Apartamento" {{ old('property_type', $property->property_type) === 'Apartamento' ? 'selected' : '' }}>Apartamento</option>
                                    <option value="Casa" {{ old('property_type', $property->property_type) === 'Casa' ? 'selected' : '' }}>Casa</option>
                                    <option value="Lote" {{ old('property_type', $property->property_type) === 'Lote' ? 'selected' : '' }}>Lote</option>
                                    <option value="Casa Lote" {{ old('property_type', $property->property_type) === 'Casa Lote' ? 'selected' : '' }}>Casa Lote</option>
                                    <option value="Oficina" {{ old('property_type', $property->property_type) === 'Oficina' ? 'selected' : '' }}>Oficina</option>
                                    <option value="Bodega" {{ old('property_type', $property->property_type) === 'Bodega' ? 'selected' : '' }}>Bodega</option>
                                    <option value="Finca" {{ old('property_type', $property->property_type) === 'Finca' ? 'selected' : '' }}>Finca</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control" value="{{ $property->property_code ?? '—' }}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Operación</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="for_sale" value="1" id="for_sale" {{ old('for_sale', $property->for_sale) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="for_sale">Venta</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="for_rent" value="1" id="for_rent" {{ old('for_rent', $property->for_rent) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="for_rent">Arriendo</label>
                                </div>
                                @error('for_sale')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Precio de venta</label>
                                <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $property->sale_price) }}">
                                @error('sale_price')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Moneda venta</label>
                                <select name="sale_currency" class="form-select">
                                    <option value="COP" {{ old('sale_currency', $property->sale_currency ?? 'COP') === 'COP' ? 'selected' : '' }}>COP</option>
                                    <option value="USD" {{ old('sale_currency', $property->sale_currency) === 'USD' ? 'selected' : '' }}>USD</option>
                                </select>
                                @error('sale_currency')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Precio de arriendo</label>
                                <input type="number" step="0.01" name="rent_price" class="form-control" value="{{ old('rent_price', $property->rent_price) }}">
                                @error('rent_price')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Moneda arriendo</label>
                                <select name="rent_currency" class="form-select">
                                    <option value="COP" {{ old('rent_currency', $property->rent_currency ?? 'COP') === 'COP' ? 'selected' : '' }}>COP</option>
                                    <option value="USD" {{ old('rent_currency', $property->rent_currency) === 'USD' ? 'selected' : '' }}>USD</option>
                                </select>
                                @error('rent_currency')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-2" data-rent-only>
                                <label class="form-label">Administración incluida</label>
                                <select name="administracion_incluida" class="form-select">
                                    <option value="">Seleccionar</option>
                                    <option value="1" {{ old('administracion_incluida', $property->administracion_incluida) == '1' ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ old('administracion_incluida', $property->administracion_incluida) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('administracion_incluida')<small class="text-danger">{{ $message }}</small>@enderror
                                <small class="text-muted">Solo aplica para arriendo.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estado comercial</label>
                                <select name="estado" class="form-select" required>
                                    <option value="disponible" {{ old('estado', $property->estado) === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="no_disponible" {{ old('estado', $property->estado) === 'no_disponible' ? 'selected' : '' }}>No disponible</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estado editorial</label>
                                <select name="status" class="form-select" required>
                                    <option value="draft" {{ old('status', $property->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                                    <option value="published" {{ old('status', $property->status) === 'published' ? 'selected' : '' }}>Publicado</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label d-block">Destacada</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $property->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Mostrar en página principal</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha publicación</label>
                                <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($property->published_at)->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sectionLocation">
                        Ubicación
                    </button>
                </h2>
                <div id="sectionLocation" class="accordion-collapse collapse" data-bs-parent="#propertyAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Ciudad</label>
                                <select name="city_id" class="form-select" required>
                                    <option value="">Selecciona una ciudad</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id', $property->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Selecciona una ciudad creada en el módulo Ciudades.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Barrio</label>
                                <input type="text" name="barrio" class="form-control" value="{{ old('barrio', $property->barrio) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $property->direccion) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">¿Es conjunto?</label>
                                <select name="is_conjunto" class="form-select">
                                    <option value="0" {{ old('is_conjunto', $property->is_conjunto) == false ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_conjunto', $property->is_conjunto) == true ? 'selected' : '' }}>Sí</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombre del conjunto</label>
                                <input type="text" name="conjunto_nombre" class="form-control" value="{{ old('conjunto_nombre', $property->conjunto_nombre) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sectionDetails">
                        Detalles y métricas
                    </button>
                </h2>
                <div id="sectionDetails" class="accordion-collapse collapse" data-bs-parent="#propertyAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Habitaciones</label>
                                <input type="number" name="habitaciones" class="form-control" value="{{ old('habitaciones', $property->habitaciones) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Baños</label>
                                <input type="number" name="banos" class="form-control" value="{{ old('banos', $property->banos) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Área m²</label>
                                <input type="number" step="0.01" name="area_m2" class="form-control" value="{{ old('area_m2', $property->area_m2) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estrato</label>
                                <select name="estrato" class="form-select">
                                    <option value="">Seleccionar</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('estrato', $property->estrato) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Parqueadero</label>
                                <select name="tiene_parqueadero" class="form-select">
                                    <option value="">Seleccionar</option>
                                    <option value="1" {{ old('tiene_parqueadero', $property->tiene_parqueadero) == true ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ old('tiene_parqueadero', $property->tiene_parqueadero) == false ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Bodega</label>
                                <select name="tiene_bodega" class="form-select">
                                    <option value="">Seleccionar</option>
                                    <option value="1" {{ old('tiene_bodega', $property->tiene_bodega) == true ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ old('tiene_bodega', $property->tiene_bodega) == false ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sectionMedia">
                        Medios
                    </button>
                </h2>
                <div id="sectionMedia" class="accordion-collapse collapse" data-bs-parent="#propertyAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Imagen principal</label>
                                <input type="file" name="imagen_principal" class="form-control" accept="image/*">
                                @if ($property->imagen_principal)
                                    <img src="{{ Storage::url($property->imagen_principal) }}" alt="Imagen principal" class="img-preview mt-2">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_main_image" value="1" id="remove_main_image">
                                        <label class="form-check-label" for="remove_main_image">Quitar imagen principal</label>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Agregar a galería</label>
                                <div class="gallery-upload" data-gallery-upload>
                                    <input type="file" name="galeria[]" class="form-control gallery-input" multiple accept="image/*">
                                    <div class="gallery-dropzone mt-2" data-gallery-dropzone>
                                        <div>
                                            <strong>Arrastra y suelta imágenes</strong>
                                            <span class="text-muted d-block">o selecciona desde tu equipo</span>
                                        </div>
                                        <button type="button" class="btn btn-outline-brand btn-sm" data-gallery-browse>Seleccionar imágenes</button>
                                    </div>
                                    <div class="gallery-upload-info mt-2" data-gallery-info></div>
                                    <div class="gallery-preview-live mt-2" data-gallery-preview></div>
                                    <small class="text-muted d-block">Arrastra las miniaturas para ordenar.</small>
                                    <div class="gallery-actions mt-2 d-none" data-gallery-actions>
                                        <button type="button" class="btn btn-light btn-sm" data-gallery-clear>Limpiar selección</button>
                                    </div>
                                </div>
                                @if ($property->galeria)
                                    <div class="gallery-existing mt-3" data-gallery-existing>
                                        <div class="gallery-existing-header">
                                            <div>
                                                <span class="text-muted small">Galería actual</span>
                                                <div class="text-muted small">Arrastra las imágenes para ordenar.</div>
                                            </div>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-brand" data-gallery-select-all>Marcar todo</button>
                                                <button type="button" class="btn btn-outline-brand" data-gallery-clear-selection>Desmarcar</button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="gallery_order" value="" data-gallery-order>
                                        <div class="gallery-preview mt-2">
                                            @foreach ($property->galeria as $index => $image)
                                                <div class="gallery-item-control" draggable="true" data-gallery-item data-gallery-path="{{ $image }}">
                                                    <img src="{{ Storage::url($image) }}" alt="Galería">
                                                    <label class="gallery-remove">
                                                        <input type="checkbox" name="remove_gallery[]" value="{{ $image }}">
                                                        Quitar
                                                    </label>
                                                    <span class="gallery-drag-hint">Arrastrar</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="gallery-existing-summary small text-muted mt-1" data-gallery-existing-summary></div>
                                    </div>
                                @endif
                                <small class="text-muted d-block mt-1">Máximo 8 MB por imagen.</small>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="clear_gallery" value="1" id="clear_gallery">
                                    <label class="form-check-label" for="clear_gallery">Limpiar galería actual</label>
                                </div>
                                <small class="text-muted d-block">Al limpiar, se eliminan todas las fotos actuales.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Video YouTube (URL)</label>
                                <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $property->youtube_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sectionSeo">
                        SEO
                    </button>
                </h2>
                <div id="sectionSeo" class="accordion-collapse collapse" data-bs-parent="#propertyAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">SEO title</label>
                                <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $property->seo_title) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SEO description</label>
                                <input type="text" name="seo_description" class="form-control" value="{{ old('seo_description', $property->seo_description) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sectionContact">
                        Contacto comercial
                    </button>
                </h2>
                <div id="sectionContact" class="accordion-collapse collapse" data-bs-parent="#propertyAccordion">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Nombre contacto</label>
                                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', $property->contact_name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Teléfono contacto</label>
                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $property->contact_phone) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email contacto</label>
                                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $property->contact_email) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción de contacto</label>
                                <textarea name="contact_description" class="form-control wysiwyg" rows="3">{{ old('contact_description', $property->contact_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.properties.index') }}" class="btn btn-light">Cancelar</a>
        <button class="btn btn-brand" type="submit">Actualizar</button>
    </div>
</form>
@endsection
