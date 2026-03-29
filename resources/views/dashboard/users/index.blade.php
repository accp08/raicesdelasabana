@extends('dashboard.layouts.app')

@section('title', 'Usuarios')

@section('header')
    <div>
        <h2>Usuarios</h2>
        <p class="text-muted">Administra las cuentas de acceso al dashboard.</p>
    </div>
    @can('create', App\Models\User::class)
        <a href="{{ route('dashboard.users.create') }}" class="btn btn-brand">Nuevo usuario</a>
    @endcan
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-capitalize">{{ $user->role }}</td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="text-end">
                                @can('update', $user)
                                    <a href="{{ route('dashboard.users.edit', $user) }}" class="btn btn-sm btn-outline-brand">Editar</a>
                                @endcan
                                @can('delete', $user)
                                    <form action="{{ route('dashboard.users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
</div>
@endsection
