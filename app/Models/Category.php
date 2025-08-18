<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    #For Admin category show
    public function categoryPost() {
        return $this->hasMany(CategoryPost::class);
    }
}
