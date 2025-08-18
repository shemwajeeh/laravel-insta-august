<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $table = 'category_post';
    protected $fillable = ['category_id', 'post_id'];
    public $timestamps = false;

    #To get the name of the category
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
