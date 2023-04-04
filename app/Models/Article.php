<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'title',
        'image',
        'url',
        'category_id',
        'source_id',
        'author_id',
        'short_description',
        'description',
        'published_at',
    ];
}
