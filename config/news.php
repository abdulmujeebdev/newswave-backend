<?php

return [
    'sources' => [
        'news_api' => [
            'name' => 'News API',
            'url' => 'https://newsapi.org/v2/top-headlines?sources=bbc-news',
            'api_key' => env('NEWS_API_KEY'),
        ],
        'newyork_times' => [
            'name' => 'Newyork Times',
            'url' => 'https://api.nytimes.com/svc/',
            'key' => env('NEWYORK_TIMES_API_KEY'),
            'secret' => env('NEWYORK_TIMES_API_SECRET'),
        ],
    ]
];
