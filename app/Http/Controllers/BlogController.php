<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index()
    {    
        $query = Post::query()->where('status', 'published');

        if ($category = request('category')) {
            $query->where('category_id', $category);
        }

        $posts = $query->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('blog.index', compact('posts', 'categories'));
    }
    public function interna()
    {
        $post = Post::where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->first();

        if (! $post) {
            return redirect()->route('blog.index')
                ->with('status', 'No hay publicaciones disponibles.');
        }

        return view('blog.interna', compact('post'));
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        Post::where('id', $post->id)->update([
            'views' => DB::raw('views + 1'),
            'last_viewed_at' => now(),
        ]);

        return view('blog.interna', compact('post'));
    }

}
