<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'name'
    ];

    public function userCategories() {
        return $this->belongsToMany(User::class, 'user_categories', 'category_id', 'user_id');
    }
}
