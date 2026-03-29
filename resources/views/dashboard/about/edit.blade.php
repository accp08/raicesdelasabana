@extends('dashboard.layouts.app')

@section('title', 'Nosotros')

@section('header')
    <div>
        <h2>Nosotros</h2>
        <p class="text-muted">Administra el contenido de la página Nosotros.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.about.update') }}" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <h5 class="mb-3">Banner principal</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Imagen banner</label>
                <input type="file" name="hero_image" class="form-control">
                @if ($about->hero_image)
                    <img src="{{ Storage::url($about->hero_image) }}" class="img-preview mt-2" alt="Banner">
                @endif
            </div>
        </div>

        <h5 class="mb-3">Sección 1</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <label class="form-label">Título</label>
                <input type="text" name="section1_title" class="form-control" value="{{ old('section1_title', $about->section1_title) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen</label>
                <input type="file" name="section1_image" class="form-control">
                @if ($about->section1_image)
                    <img src="{{ Storage::url($about->section1_image) }}" class="img-preview mt-2" alt="Sección 1">
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Texto</label>
                <textarea name="section1_body" class="form-control wysiwyg" rows="4">{{ old('section1_body', $about->section1_body) }}</textarea>
            </div>
        </div>

        <h5 class="mb-3">Sección 2</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <label class="form-label">Título</label>
                <input type="text" name="section2_title" class="form-control" value="{{ old('section2_title', $about->section2_title) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen</label>
                <input type="file" name="section2_image" class="form-control">
                @if ($about->section2_image)
                    <img src="{{ Storage::url($about->section2_image) }}" class="img-preview mt-2" alt="Sección 2">
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Texto</label>
                <textarea name="section2_body" class="form-control wysiwyg" rows="4">{{ old('section2_body', $about->section2_body) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Items (líneas de negocio)</label>
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" name="section2_items[]" class="form-control mb-2" value="{{ old('section2_items.'.$i, $about->section2_items[$i] ?? '') }}">
                @endfor
            </div>
        </div>

        <h5 class="mb-3">Sección 3</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <label class="form-label">Título</label>
                <input type="text" name="section3_title" class="form-control" value="{{ old('section3_title', $about->section3_title) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen</label>
                <input type="file" name="section3_image" class="form-control">
                @if ($about->section3_image)
                    <img src="{{ Storage::url($about->section3_image) }}" class="img-preview mt-2" alt="Sección 3">
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Texto</label>
                <textarea name="section3_body" class="form-control wysiwyg" rows="4">{{ old('section3_body', $about->section3_body) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Items (beneficios)</label>
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" name="section3_items[]" class="form-control mb-2" value="{{ old('section3_items.'.$i, $about->section3_items[$i] ?? '') }}">
                @endfor
            </div>
        </div>

        <h5 class="mb-3">Sección 4</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <label class="form-label">Título</label>
                <input type="text" name="section4_title" class="form-control" value="{{ old('section4_title', $about->section4_title) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen</label>
                <input type="file" name="section4_image" class="form-control">
                @if ($about->section4_image)
                    <img src="{{ Storage::url($about->section4_image) }}" class="img-preview mt-2" alt="Sección 4">
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Texto</label>
                <textarea name="section4_body" class="form-control wysiwyg" rows="4">{{ old('section4_body', $about->section4_body) }}</textarea>
            </div>
        </div>

        <h5 class="mb-3">Sección 5</h5>
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <label class="form-label">Título</label>
                <input type="text" name="section5_title" class="form-control" value="{{ old('section5_title', $about->section5_title) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen</label>
                <input type="file" name="section5_image" class="form-control">
                @if ($about->section5_image)
                    <img src="{{ Storage::url($about->section5_image) }}" class="img-preview mt-2" alt="Sección 5">
                @endif
            </div>
            <div class="col-12">
                <label class="form-label">Texto</label>
                <textarea name="section5_body" class="form-control wysiwyg" rows="4">{{ old('section5_body', $about->section5_body) }}</textarea>
            </div>
        </div>

        <h5 class="mb-3">Contacto</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nombre</label>
                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', $about->contact_name) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Cargo</label>
                <input type="text" name="contact_role" class="form-control" value="{{ old('contact_role', $about->contact_role) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $about->contact_phone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $about->contact_email) }}">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <button class="btn btn-brand" type="submit">Guardar cambios</button>
    </div>
</form>
@endsection
