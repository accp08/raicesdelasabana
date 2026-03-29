@extends('dashboard.layouts.app')

@section('title', 'Editar categoría')

@section('header')
    <div>
        <h2>Editar categoría</h2>
        <p class="text-muted">Actualiza la categoría seleccionada.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.categories.update', $category) }}" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-light">Cancelar</a>
        <button class="btn btn-brand" type="submit">Actualizar</button>
    </div>
</form>
@endsection
