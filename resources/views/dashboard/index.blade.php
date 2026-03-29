@extends('dashboard.layouts.app')

@section('title', 'Dashboard')

@section('header')
    <div>
        <h2>Resumen general</h2>
        <p class="text-muted">Estado del inventario y visitas.</p>
    </div>
@endsection

@section('content')
<div class="row g-4 dashboard-stats">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-head">
                <span class="stat-icon">📩</span>
                <h6>Contactos</h6>
            </div>
            <span class="stat-value">{{ $leadsCount }}</span>
        </div>
    </div>
</div>

<div class="stats-section">
    <h5>Propiedades</h5>
    <div class="row g-4 dashboard-stats">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-icon">🏘️</span>
                    <h6>Total</h6>
                </div>
                <span class="stat-value">{{ $propertiesCount }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-icon">✅</span>
                    <h6>Publicadas</h6>
                </div>
                <span class="stat-value">{{ $propertiesPublished }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-icon">📝</span>
                    <h6>Borrador</h6>
                </div>
                <span class="stat-value">{{ $propertiesDraft }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-icon">👁️</span>
                    <h6>Visitas</h6>
                </div>
                <span class="stat-value">{{ $propertiesViews }}</span>
            </div>
        </div>
    </div>
</div>

<div class="stats-section">
    <h5>Blog</h5>
    <div class="row g-4 dashboard-stats">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-icon">📰</span>
                    <h6>Total</h6>
                </div>
                <span class="stat-value">{{ $postsCount }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-icon">✅</span>
                    <h6>Publicados</h6>
                </div>
                <span class="stat-value">{{ $postsPublished }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-icon">📝</span>
                    <h6>Borrador</h6>
                </div>
                <span class="stat-value">{{ $postsDraft }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-head">
                    <span class="stat-icon">👁️</span>
                    <h6>Visitas</h6>
                </div>
                <span class="stat-value">{{ $postsViews }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
