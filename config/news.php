<?php

return [
    'sources' => [
        'news_api' => [
            'name' => 'News API',
            'url' => 'https://newsapi.org/v2/top-headlines',
            'api_key' => env('NEWS_API_KEY'),
        ],
        'newyork_times' => [
            'name' => 'Newyork Times',
            'url' => 'https://api.nytimes.com/svc/',
            'api_key' => env('NEWYORK_TIMES_API_KEY'),
            'secret' => env('NEWYORK_TIMES_API_SECRET'),
        ],
        'the_guardians' => [
            'name' => 'The Guardians',
            'url' => 'https://content.guardianapis.com/',
            'api_key' => env('THE_GUARDIANS_API_KEY'),
            'secret' => env('NEWYORK_TIMES_API_SECRET'),
        ],
    ],

    'categories' => ['science', 'politics', 'sports', 'business', 'health', 'technology']
];
