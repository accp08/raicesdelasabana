@extends('dashboard.layouts.app')

@section('title', 'Contactos')

@section('header')
    <div>
        <h2>Contactos</h2>
        <p class="text-muted">Solicitudes recibidas desde las propiedades.</p>
    </div>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, email o teléfono" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="nuevo" {{ request('status') === 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                    <option value="contactado" {{ request('status') === 'contactado' ? 'selected' : '' }}>Contactado</option>
                    <option value="agendado" {{ request('status') === 'agendado' ? 'selected' : '' }}>Agendado</option>
                    <option value="cerrado" {{ request('status') === 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                    <option value="descartado" {{ request('status') === 'descartado' ? 'selected' : '' }}>Descartado</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="property_id" class="form-select">
                    <option value="">Todas las propiedades</option>
                    @foreach ($properties as $property)
                        <option value="{{ $property->id }}" {{ request('property_id') == $property->id ? 'selected' : '' }}>
                            {{ $property->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button class="btn btn-outline-brand w-100" type="submit">Filtrar</button>
                <a href="{{ route('dashboard.leads.index') }}" class="btn btn-light w-100">Limpiar</a>
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
                        <th>Código</th>
                        <th>Propiedad</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td>{{ $lead->property?->property_code ?? '—' }}</td>
                            <td>{{ $lead->property?->titulo ?? '—' }}</td>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->phone ?? '—' }}</td>
                            <td><span class="badge bg-secondary text-capitalize">{{ $lead->status }}</span></td>
                            <td>{{ $lead->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('dashboard.leads.show', $lead) }}" class="btn btn-sm btn-outline-brand">Ver</a>
                                @can('delete', $lead)
                                    <form action="{{ route('dashboard.leads.destroy', $lead) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('¿Seguro que deseas eliminar este contacto?')">Eliminar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $leads->links() }}
    </div>
</div>
@endsection
