<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:properties,slug'],
            'descripcion_corta' => ['nullable', 'string', 'max:250'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'ciudad' => ['nullable', 'string', 'max:120'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'property_type' => ['nullable', 'string', 'max:50', 'in:Apartamento,Casa,Lote,Casa Lote,Oficina,Bodega,Finca'],
            'is_conjunto' => ['nullable', 'boolean'],
            'conjunto_nombre' => ['nullable', 'string', 'max:255', 'required_if:is_conjunto,1'],
            'barrio' => ['nullable', 'string', 'max:255'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_description' => ['nullable', 'string', 'max:2000'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'tipo' => ['nullable', 'string', 'max:50'],
            'for_sale' => ['nullable', 'boolean'],
            'for_rent' => ['nullable', 'boolean'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'required_if:for_sale,1'],
            'sale_currency' => ['nullable', 'string', 'in:COP,USD', 'required_if:for_sale,1'],
            'rent_price' => ['nullable', 'numeric', 'min:0', 'required_if:for_rent,1'],
            'rent_currency' => ['nullable', 'string', 'in:COP,USD', 'required_if:for_rent,1'],
            'administracion_incluida' => ['required_if:for_rent,1', 'boolean'],
            'estado' => ['required', 'in:disponible,no_disponible'],
            'habitaciones' => ['nullable', 'integer', 'min:0', 'max:50'],
            'banos' => ['nullable', 'integer', 'min:0', 'max:50'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'estrato' => ['nullable', 'integer', 'min:1', 'max:6'],
            'tiene_parqueadero' => ['nullable', 'boolean'],
            'tiene_bodega' => ['nullable', 'boolean'],
            'imagen_principal' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'galeria' => ['nullable', 'array'],
            'galeria.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'youtube_url' => ['nullable', 'url', 'max:255', 'regex:/^(https?:\\/\\/)?(www\\.)?(youtube\\.com|youtu\\.be)\\/.+/i'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $forSale = $this->boolean('for_sale');
            $forRent = $this->boolean('for_rent');
            if (! $forSale && ! $forRent) {
                $validator->errors()->add('for_sale', 'Debes seleccionar Venta, Arriendo o ambos.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'sale_price.required_if' => 'El precio de venta es obligatorio cuando seleccionas venta.',
            'sale_currency.required_if' => 'La moneda de venta es obligatoria cuando seleccionas venta.',
            'rent_price.required_if' => 'El precio de arriendo es obligatorio cuando seleccionas arriendo.',
            'rent_currency.required_if' => 'La moneda de arriendo es obligatoria cuando seleccionas arriendo.',
            'administracion_incluida.required_if' => 'Debes indicar si la administración está incluida cuando seleccionas arriendo.',
        ];
    }

    public function attributes(): array
    {
        return [
            'sale_price' => 'precio de venta',
            'sale_currency' => 'moneda de venta',
            'rent_price' => 'precio de arriendo',
            'rent_currency' => 'moneda de arriendo',
        ];
    }
}
