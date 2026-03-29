<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutPageUpdateRequest;
use App\Models\AboutPage;
use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewAny,'.AboutPage::class)->only('edit');
    }

    public function edit()
    {
        $about = AboutPage::first();

        if (! $about) {
            $about = AboutPage::create([
                'section1_title' => '¿Quiénes somos?',
                'section2_title' => 'Líneas de negocio',
                'section3_title' => '¿Por qué elegirnos?',
                'section4_title' => 'Nuestros clientes lo confirman',
                'section5_title' => 'Contáctanos',
            ]);
        }

        $this->authorize('view', $about);

        return view('dashboard.about.edit', compact('about'));
    }

    public function update(AboutPageUpdateRequest $request)
    {
        $about = AboutPage::firstOrFail();
        $this->authorize('update', $about);
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        if ($request->hasFile('hero_image')) {
            if ($about->hero_image) {
                Storage::disk('public')->delete($about->hero_image);
            }
            $optimizer = app(ImageOptimizer::class);
            if ($about->hero_image_thumb) {
                Storage::disk('public')->delete($about->hero_image_thumb);
            }
            $stored = $optimizer->storeWithThumbnail($request->file('hero_image'), 'about');
            $data['hero_image'] = $stored['image'];
            $data['hero_image_thumb'] = $stored['thumb'];
        }

        foreach (['section1_image','section2_image','section3_image','section4_image','section5_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($about->$field) {
                    Storage::disk('public')->delete($about->$field);
                }
                $optimizer = app(ImageOptimizer::class);
                $thumbField = $field.'_thumb';
                if (!empty($about->$thumbField)) {
                    Storage::disk('public')->delete($about->$thumbField);
                }
                $stored = $optimizer->storeWithThumbnail($request->file($field), 'about');
                $data[$field] = $stored['image'];
                $data[$thumbField] = $stored['thumb'];
            }
        }

        if (!empty($data['section2_items'])) {
            $data['section2_items'] = array_values(array_filter($data['section2_items']));
        }

        if (!empty($data['section3_items'])) {
            $data['section3_items'] = array_values(array_filter($data['section3_items']));
        }

        $about->update($data);

        return redirect()->route('dashboard.about.edit')->with('status', 'Contenido actualizado.');
    }
}
