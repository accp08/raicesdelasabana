<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index()
    {
        $query = Post::with('category');

        if ($search = request('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($category = request('category')) {
            $query->where('category_id', $category);
        }

        $posts = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('dashboard.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('dashboard.posts.create', compact('categories', 'tags'));
    }

    public function store(PostStoreRequest $request)
    {
        $data = $request->validated();
        $data['excerpt'] = $this->normalizeHtml($data['excerpt'] ?? null);
        $data['content'] = $this->normalizeHtml($data['content'] ?? null);
        $data['slug'] = $this->makeUniqueSlug(Post::class, $data['slug'] ?? $data['title']);
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $data['category_id'] = $this->resolveCategory($data['category_id'] ?? null, $data['new_category'] ?? null);

        if ($request->hasFile('cover_image')) {
            $optimizer = app(ImageOptimizer::class);
            $stored = $optimizer->storeWithThumbnail($request->file('cover_image'), 'posts');
            $data['cover_image'] = $stored['image'];
            $data['cover_image_thumb'] = $stored['thumb'];
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($data['status'] !== 'published') {
            $data['published_at'] = null;
        }

        $post = Post::create($data);
        $this->syncTags($post, $data['tags'] ?? [], $data['tags_input'] ?? null);

        return redirect()->route('dashboard.posts.index')
            ->with('status', 'Post creado correctamente.');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $post->load('tags');

        return view('dashboard.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(PostUpdateRequest $request, Post $post)
    {
        $data = $request->validated();
        $data['excerpt'] = $this->normalizeHtml($data['excerpt'] ?? null);
        $data['content'] = $this->normalizeHtml($data['content'] ?? null);
        $data['slug'] = $this->makeUniqueSlug(Post::class, $data['slug'] ?? $data['title'], $post->id);
        $data['updated_by'] = $request->user()->id;
        $data['category_id'] = $this->resolveCategory($data['category_id'] ?? null, $data['new_category'] ?? null);

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            if ($post->cover_image_thumb) {
                Storage::disk('public')->delete($post->cover_image_thumb);
            }
            $optimizer = app(ImageOptimizer::class);
            $stored = $optimizer->storeWithThumbnail($request->file('cover_image'), 'posts');
            $data['cover_image'] = $stored['image'];
            $data['cover_image_thumb'] = $stored['thumb'];
        }

        if (!empty($data['remove_cover'])) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            if ($post->cover_image_thumb) {
                Storage::disk('public')->delete($post->cover_image_thumb);
            }
            $data['cover_image'] = null;
            $data['cover_image_thumb'] = null;
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($data['status'] !== 'published') {
            $data['published_at'] = null;
        }

        $post->update($data);
        $this->syncTags($post, $data['tags'] ?? [], $data['tags_input'] ?? null);

        return redirect()->route('dashboard.posts.index')
            ->with('status', 'Post actualizado.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('dashboard.posts.index')
            ->with('status', 'Post eliminado.');
    }

    private function makeUniqueSlug(string $modelClass, string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $counter = 1;

        while ($modelClass::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function resolveCategory(?int $categoryId, ?string $newCategory): ?int
    {
        if ($newCategory) {
            $category = Category::firstOrCreate([
                'slug' => Str::slug($newCategory),
            ], [
                'name' => $newCategory,
            ]);

            return $category->id;
        }

        return $categoryId;
    }

    private function syncTags(Post $post, array $tags, ?string $tagsInput): void
    {
        $tagIds = [];

        if ($tagsInput) {
            $tagsInput = collect(explode(',', $tagsInput))
                ->map(fn ($tag) => trim($tag))
                ->filter();

            foreach ($tagsInput as $tagName) {
                $tag = Tag::firstOrCreate([
                    'slug' => Str::slug($tagName),
                ], [
                    'name' => $tagName,
                ]);
                $tagIds[] = $tag->id;
            }
        }

        foreach ($tags as $tagId) {
            $tagIds[] = (int) $tagId;
        }

        $post->tags()->sync(array_unique($tagIds));
    }

    private function normalizeHtml(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim($decoded) === '' ? null : $decoded;
    }
}
