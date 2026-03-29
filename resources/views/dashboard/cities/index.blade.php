@extends('dashboard.layouts.app')

@section('title', 'Ciudades')

@section('header')
    <div>
        <h2>Ciudades</h2>
        <p class="text-muted">Administra las ciudades disponibles.</p>
    </div>
    @can('create', App\Models\City::class)
        <a href="{{ route('dashboard.cities.create') }}" class="btn btn-brand">Nueva ciudad</a>
    @endcan
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cities as $city)
                        <tr>
                            <td>{{ $city->name }}</td>
                            <td>
                                <span class="badge {{ $city->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $city->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="text-end">
                                @can('update', $city)
                                    <a href="{{ route('dashboard.cities.edit', $city) }}" class="btn btn-sm btn-outline-brand">Editar</a>
                                @endcan
                                @can('delete', $city)
                                    <form action="{{ route('dashboard.cities.destroy', $city) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $cities->links() }}
    </div>
</div>
@endsection
