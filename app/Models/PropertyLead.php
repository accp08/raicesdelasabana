<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLead extends Model
{
    protected $fillable = [
        'property_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
        'source_page',
        'ip_address',
        'user_agent',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
