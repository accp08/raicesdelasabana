<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Property;
use Illuminate\Http\Response;
class SitemapController extends Controller
{
    public function index(): Response
    {
        $latestPropertyUpdate = Property::query()
            ->where('status', 'published')
            ->orderByDesc('updated_at')
            ->first(['updated_at']);

        $latestPostUpdate = Post::query()
            ->where('status', 'published')
            ->orderByDesc('updated_at')
            ->first(['updated_at']);

        $staticUrls = collect([
            [
                'loc' => url('/'),
                'lastmod' => $latestPropertyUpdate?->updated_at?->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => url('/nosotros'),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => url('/blog'),
                'lastmod' => $latestPostUpdate?->updated_at?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => url('/propiedades'),
                'lastmod' => $latestPropertyUpdate?->updated_at?->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
        ]);

        $urls = collect($staticUrls);

        $properties = Property::query()
            ->where('status', 'published')
            ->whereNotNull('slug')
            ->orderByDesc('updated_at')
            ->get(['slug', 'published_at', 'updated_at']);

        foreach ($properties as $property) {
            $urls->push([
                'loc' => url('/propiedades/'.$property->slug),
                'lastmod' => ($property->updated_at ?: $property->published_at)?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ]);
        }

        $posts = Post::query()
            ->where('status', 'published')
            ->whereNotNull('slug')
            ->orderByDesc('updated_at')
            ->get(['slug', 'published_at', 'updated_at']);

        foreach ($posts as $post) {
            $urls->push([
                'loc' => url('/blog/'.$post->slug),
                'lastmod' => ($post->updated_at ?: $post->published_at)?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ]);
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
