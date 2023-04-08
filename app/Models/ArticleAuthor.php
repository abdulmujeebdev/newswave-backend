<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleAuthor extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'name'
    ];

    public function userAuthors() {
        return $this->belongsToMany(User::class, 'user_authors', 'author_id', 'user_id');
    }
}
