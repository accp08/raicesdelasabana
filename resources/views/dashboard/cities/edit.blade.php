@extends('dashboard.layouts.app')

@section('title', 'Editar ciudad')

@section('header')
    <div>
        <h2>Editar ciudad</h2>
        <p class="text-muted">Actualiza la ciudad seleccionada.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.cities.update', $city) }}" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $city->name) }}" required>
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $city->is_active) == true ? 'selected' : '' }}>Activa</option>
                    <option value="0" {{ old('is_active', $city->is_active) == false ? 'selected' : '' }}>Inactiva</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.cities.index') }}" class="btn btn-light">Cancelar</a>
        <button class="btn btn-brand" type="submit">Actualizar</button>
    </div>
</form>
@endsection
