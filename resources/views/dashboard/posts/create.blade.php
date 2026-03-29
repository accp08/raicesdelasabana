@extends('dashboard.layouts.app')

@section('title', 'Nuevo post')

@section('header')
    <div>
        <h2>Nuevo post</h2>
        <p class="text-muted">Crea contenido para el blog.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.posts.store') }}" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Título</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="auto-generado">
            </div>
            <div class="col-12">
                <label class="form-label">Extracto</label>
                <textarea name="excerpt" class="form-control wysiwyg" rows="2">{{ old('excerpt') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Contenido</label>
                <textarea name="content" class="form-control wysiwyg" rows="6">{{ old('content') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Imagen de portada</label>
                <input type="file" name="cover_image" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select">
                    <option value="">Sin categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Si no existe, crea una nueva.</small>
                <input type="text" name="new_category" class="form-control mt-2" placeholder="Nueva categoría" value="{{ old('new_category') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">SEO title</label>
                <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">SEO description</label>
                <input type="text" name="seo_description" class="form-control" value="{{ old('seo_description') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select" required>
                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha publicación</label>
                <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at') }}">
            </div>
            <div class="col-md-12">
                <label class="form-label">Tags existentes</label>
                <select name="tags[]" class="form-select" multiple>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">O agrega nuevos tags separados por coma.</small>
                <input type="text" name="tags_input" class="form-control mt-2" placeholder="ej: inversión, tendencias" value="{{ old('tags_input') }}">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.posts.index') }}" class="btn btn-light">Cancelar</a>
        <button class="btn btn-brand" type="submit">Guardar</button>
    </div>
</form>
@endsection
