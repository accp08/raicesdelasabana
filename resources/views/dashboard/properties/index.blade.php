@extends('dashboard.layouts.app')

@section('title', 'Propiedades')

@section('header')
    <div>
        <h2>Propiedades</h2>
        <p class="text-muted">Gestiona el inventario de propiedades.</p>
    </div>
    @can('create', App\Models\Property::class)
        <a href="{{ route('dashboard.properties.create') }}" class="btn btn-brand">Nueva propiedad</a>
    @endcan
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Buscar por título o ciudad" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Estado</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="tipo" class="form-select">
                    <option value="">Tipo</option>
                    <option value="venta" {{ request('tipo') === 'venta' ? 'selected' : '' }}>Venta</option>
                    <option value="arriendo" {{ request('tipo') === 'arriendo' ? 'selected' : '' }}>Arriendo</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="ciudad" class="form-control" placeholder="Ciudad" value="{{ request('ciudad') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-brand w-100" type="submit">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Código</th>
                        <th>Destacada</th>
                        <th>Ciudad</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Publicación</th>
                        <th>Visitas</th>
                        <th>Última visita</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($properties as $property)
                        <tr>
                            <td>{{ $property->titulo }}</td>
                            <td class="text-muted">{{ $property->property_code ?? '—' }}</td>
                            <td>
                                @if ($property->is_featured)
                                    <span class="badge bg-warning text-dark">⭐</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $property->ciudad }}</td>
                            <td class="text-capitalize">{{ $property->tipo }}</td>
                            <td>
                                <span class="badge {{ $property->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $property->status === 'published' ? 'Publicado' : 'Borrador' }}
                                </span>
                            </td>
                            <td>{{ $property->published_at?->format('Y-m-d') ?? '—' }}</td>
                            <td>{{ $property->views ?? 0 }}</td>
                            <td>{{ $property->last_viewed_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="text-end">
                                @can('update', $property)
                                    <a href="{{ route('dashboard.properties.edit', $property) }}" class="btn btn-sm btn-outline-brand">Editar</a>
                                @endcan
                                @can('delete', $property)
                                    <form action="{{ route('dashboard.properties.destroy', $property) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('¿Seguro que deseas eliminar esta propiedad?')">Eliminar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $properties->links() }}
    </div>
</div>
@endsection
