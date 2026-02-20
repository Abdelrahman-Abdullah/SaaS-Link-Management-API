<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $table = 'clicks';
    protected $fillable = [
        'link_id',
        'clicked_at',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'region',
        'city',
        'device_type',
        'browser',
        'platform'
    ];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
