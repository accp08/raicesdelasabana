@extends('dashboard.layouts.app')

@section('title', 'Crear usuario')

@section('header')
    <div>
        <h2>Nuevo usuario</h2>
        <p class="text-muted">Crea un acceso con rol admin o editor.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.users.store') }}" class="card shadow-sm">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Rol</label>
                <select name="role" class="form-select" required>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="editor" {{ old('role', 'editor') === 'editor' ? 'selected' : '' }}>Editor</option>
                </select>
                @error('role')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.users.index') }}" class="btn btn-light">Cancelar</a>
        <button class="btn btn-brand" type="submit">Guardar</button>
    </div>
</form>
@endsection
