@extends('dashboard.layouts.app')

@section('title', 'Nueva categoría')

@section('header')
    <div>
        <h2>Nueva categoría</h2>
        <p class="text-muted">Agrega una categoría para el blog.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.categories.store') }}" class="card shadow-sm">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-light">Cancelar</a>
        <button class="btn btn-brand" type="submit">Guardar</button>
    </div>
</form>
@endsection
