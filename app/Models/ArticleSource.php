<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleSource extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'name'
    ];

    public function userSources() {
        return $this->belongsToMany(User::class, 'user_sources', 'source_id', 'user_id');
    }
}
