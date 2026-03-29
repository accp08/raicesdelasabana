<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cityId = $this->route('city')?->id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:cities,name,'.$cityId],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
