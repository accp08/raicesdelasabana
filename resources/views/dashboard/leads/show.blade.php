@extends('dashboard.layouts.app')

@section('title', 'Detalle contacto')

@section('header')
    <div>
        <h2>Detalle contacto</h2>
        <p class="text-muted">Solicitud enviada desde la web.</p>
    </div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <h6>Propiedad</h6>
                <p>{{ $lead->property?->titulo ?? '—' }}</p>
            </div>
            <div class="col-md-6">
                <h6>Fecha</h6>
                <p>{{ $lead->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div class="col-md-6">
                <h6>Nombre</h6>
                <p>{{ $lead->name }}</p>
            </div>
            <div class="col-md-6">
                <h6>Email</h6>
                <p>{{ $lead->email }}</p>
            </div>
            <div class="col-md-6">
                <h6>Teléfono</h6>
                <p>{{ $lead->phone ?? '—' }}</p>
            </div>
            <div class="col-12">
                <h6>Mensaje</h6>
                <p>{{ $lead->message ?? '—' }}</p>
            </div>
            <div class="col-12">
                <h6>Origen</h6>
                <p>{{ $lead->source_page ?? '—' }}</p>
            </div>
            <div class="col-12">
                <h6>Estado comercial</h6>
                <form method="POST" action="{{ route('dashboard.leads.update', $lead) }}" class="row g-2">
                    @csrf
                    @method('PUT')
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="nuevo" {{ $lead->status === 'nuevo' ? 'selected' : '' }}>Nuevo</option>
                            <option value="contactado" {{ $lead->status === 'contactado' ? 'selected' : '' }}>Contactado</option>
                            <option value="agendado" {{ $lead->status === 'agendado' ? 'selected' : '' }}>Agendado</option>
                            <option value="cerrado" {{ $lead->status === 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                            <option value="descartado" {{ $lead->status === 'descartado' ? 'selected' : '' }}>Descartado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-brand w-100" type="submit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('dashboard.leads.index') }}" class="btn btn-light">Volver</a>
        @can('delete', $lead)
            <form action="{{ route('dashboard.leads.destroy', $lead) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger" type="submit" onclick="return confirm('¿Seguro que deseas eliminar este contacto?')">Eliminar</button>
            </form>
        @endcan
    </div>
</div>
@endsection
