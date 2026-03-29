<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = [
        'hero_image',
        'hero_image_thumb',
        'section1_title',
        'section1_body',
        'section1_image',
        'section1_image_thumb',
        'section2_title',
        'section2_body',
        'section2_image',
        'section2_image_thumb',
        'section2_items',
        'section3_title',
        'section3_body',
        'section3_image',
        'section3_image_thumb',
        'section3_items',
        'section4_title',
        'section4_body',
        'section4_image',
        'section4_image_thumb',
        'section5_title',
        'section5_body',
        'section5_image',
        'section5_image_thumb',
        'contact_name',
        'contact_role',
        'contact_phone',
        'contact_email',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'section2_items' => 'array',
        'section3_items' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
