<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = 'links';
    protected $fillable = [
        'user_id',
        'original_url',
        'short_code',
        'custom_alias',
        'title',
        'expires_at',
        'is_active',
        'clicks_count',
        'last_status_update'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clicks()
    {
        return $this->hasMany(Click::class);
    }

}
