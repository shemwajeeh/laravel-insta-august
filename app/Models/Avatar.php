<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $fillable = ['user_id', 'config', 'preview_url'];
    protected $casts = [
        'config' => 'array',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
