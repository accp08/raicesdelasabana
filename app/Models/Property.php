<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\City;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titulo',
        'slug',
        'descripcion_corta',
        'descripcion',
        'precio',
        'ciudad',
        'city_id',
        'property_type',
        'is_conjunto',
        'conjunto_nombre',
        'barrio',
        'contact_name',
        'contact_phone',
        'contact_email',
        'contact_description',
        'direccion',
        'tipo',
        'for_sale',
        'for_rent',
        'sale_price',
        'rent_price',
        'administracion_incluida',
        'estado',
        'habitaciones',
        'banos',
        'area_m2',
        'estrato',
        'tiene_parqueadero',
        'tiene_bodega',
        'imagen_principal',
        'imagen_principal_thumb',
        'galeria',
        'galeria_thumbs',
        'youtube_url',
        'seo_title',
        'seo_description',
        'status',
        'published_at',
        'is_featured',
        'property_code',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'galeria' => 'array',
        'galeria_thumbs' => 'array',
        'published_at' => 'datetime',
        'precio' => 'decimal:2',
        'area_m2' => 'decimal:2',
        'is_conjunto' => 'boolean',
        'last_viewed_at' => 'datetime',
        'tiene_parqueadero' => 'boolean',
        'tiene_bodega' => 'boolean',
        'for_sale' => 'boolean',
        'for_rent' => 'boolean',
        'sale_price' => 'decimal:2',
        'rent_price' => 'decimal:2',
        'administracion_incluida' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function leads()
    {
        return $this->hasMany(PropertyLead::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
