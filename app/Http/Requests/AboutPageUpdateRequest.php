<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutPageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'section1_title' => ['nullable', 'string', 'max:255'],
            'section1_body' => ['nullable', 'string'],
            'section1_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'section2_title' => ['nullable', 'string', 'max:255'],
            'section2_body' => ['nullable', 'string'],
            'section2_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'section2_items' => ['nullable', 'array'],
            'section2_items.*' => ['nullable', 'string', 'max:255'],
            'section3_title' => ['nullable', 'string', 'max:255'],
            'section3_body' => ['nullable', 'string'],
            'section3_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'section3_items' => ['nullable', 'array'],
            'section3_items.*' => ['nullable', 'string', 'max:255'],
            'section4_title' => ['nullable', 'string', 'max:255'],
            'section4_body' => ['nullable', 'string'],
            'section4_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'section5_title' => ['nullable', 'string', 'max:255'],
            'section5_body' => ['nullable', 'string'],
            'section5_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_role' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
