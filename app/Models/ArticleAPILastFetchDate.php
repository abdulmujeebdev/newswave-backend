<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleAPILastFetchDate extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'article_api_last_fetch_date';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'last_published_date',
    ];
}
