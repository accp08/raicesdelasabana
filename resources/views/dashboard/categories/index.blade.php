@extends('dashboard.layouts.app')

@section('title', 'Categorías')

@section('header')
    <div>
        <h2>Categorías</h2>
        <p class="text-muted">Administra las categorías del blog.</p>
    </div>
    @can('create', App\Models\Category::class)
        <a href="{{ route('dashboard.categories.create') }}" class="btn btn-brand">Nueva categoría</a>
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
                        <th>Slug</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->slug }}</td>
                            <td class="text-end">
                                @can('update', $category)
                                    <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-sm btn-outline-brand">Editar</a>
                                @endcan
                                @can('delete', $category)
                                    <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" class="d-inline">
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
        {{ $categories->links() }}
    </div>
</div>
@endsection
