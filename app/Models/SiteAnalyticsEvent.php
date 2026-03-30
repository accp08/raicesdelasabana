<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteAnalyticsEvent extends Model
{
    protected $fillable = [
        'event_type',
        'page_path',
        'page_url',
        'target_url',
        'target_text',
        'cta_key',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'session_id',
        'ip_address',
        'user_agent',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];
}
