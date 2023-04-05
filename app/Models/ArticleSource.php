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
        return $this->hasOne(UserSources::class);
    }
}
