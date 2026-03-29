@extends('dashboard.layouts.app')

@section('title', 'Editar usuario')

@section('header')
    <div>
        <h2>Editar usuario</h2>
        <p class="text-muted">Actualiza datos, rol o estado.</p>
    </div>
@endsection

@section('content')
<form method="POST" action="{{ route('dashboard.users.update', $user) }}" class="card shadow-sm">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nueva contraseña</label>
                <input type="password" name="password" class="form-control">
                <small class="text-muted">Dejar vacío para mantener la actual.</small>
                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Rol</label>
                <select name="role" class="form-select" required>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                </select>
                @error('role')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $user->is_active) == true ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('is_active', $user->is_active) == false ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.users.index') }}" class="btn btn-light">Cancelar</a>
        <button class="btn btn-brand" type="submit">Actualizar</button>
    </div>
</form>
@endsection
