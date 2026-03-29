@extends('dashboard.layouts.app')

@section('title', 'Blog')

@section('header')
    <div>
        <h2>Blog</h2>
        <p class="text-muted">Administra los contenidos del blog.</p>
    </div>
    @can('create', App\Models\Post::class)
        <a href="{{ route('dashboard.posts.create') }}" class="btn btn-brand">Nuevo post</a>
    @endcan
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Buscar por título" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Estado</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
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
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Publicación</th>
                        <th>Visitas</th>
                        <th>Última visita</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->category?->name ?? 'Sin categoría' }}</td>
                            <td>
                                <span class="badge {{ $post->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $post->status === 'published' ? 'Publicado' : 'Borrador' }}
                                </span>
                            </td>
                            <td>{{ $post->published_at?->format('Y-m-d') ?? '—' }}</td>
                            <td>{{ $post->views ?? 0 }}</td>
                            <td>{{ $post->last_viewed_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td class="text-end">
                                @can('update', $post)
                                    <a href="{{ route('dashboard.posts.edit', $post) }}" class="btn btn-sm btn-outline-brand">Editar</a>
                                @endcan
                                @can('delete', $post)
                                    <form action="{{ route('dashboard.posts.destroy', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('¿Seguro que deseas eliminar esta publicación?')">Eliminar</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $posts->links() }}
    </div>
</div>
@endsection
