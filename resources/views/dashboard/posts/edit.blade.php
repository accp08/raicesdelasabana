@extends('dashboard.layouts.app')

@section('title', 'Editar post')

@section('header')
    <div>
        <h2>Editar post</h2>
        <p class="text-muted">Actualiza el contenido del blog.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.posts.update', $post) }}" enctype="multipart/form-data" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Título</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $post->slug) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Extracto</label>
                <textarea name="excerpt" class="form-control wysiwyg" rows="2">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Contenido</label>
                <textarea name="content" class="form-control wysiwyg" rows="6">{{ old('content', $post->content) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Imagen de portada</label>
                <input type="file" name="cover_image" class="form-control">
                @if ($post->cover_image)
                    <img src="{{ Storage::url($post->cover_image) }}" alt="Portada" class="img-preview mt-2">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="remove_cover" value="1" id="remove_cover">
                        <label class="form-check-label" for="remove_cover">Eliminar portada actual</label>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select">
                    <option value="">Sin categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Si no existe, crea una nueva.</small>
                <input type="text" name="new_category" class="form-control mt-2" placeholder="Nueva categoría" value="{{ old('new_category') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">SEO title</label>
                <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $post->seo_title) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">SEO description</label>
                <input type="text" name="seo_description" class="form-control" value="{{ old('seo_description', $post->seo_description) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="status" class="form-select" required>
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha publicación</label>
                <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="col-md-12">
                <label class="form-label">Tags existentes</label>
                <select name="tags[]" class="form-select" multiple>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" {{ $post->tags->contains($tag) ? 'selected' : '' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
                <small class="text-muted">O agrega nuevos tags separados por coma.</small>
                <input type="text" name="tags_input" class="form-control mt-2" placeholder="ej: inversión, tendencias" value="{{ old('tags_input') }}">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.posts.index') }}" class="btn btn-light">Cancelar</a>
        <button class="btn btn-brand" type="submit">Actualizar</button>
    </div>
</form>
@endsection
