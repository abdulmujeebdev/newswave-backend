<?php

return [
    'sources' => [
        'news_api' => [
            'name' => 'News API',
            'url' => 'https://newsapi.org/v2/top-headlines?sources=bbc-news',
            'api_key' => env('NEWS_API_KEY'),
        ],
        'bbc' => [
            'name' => 'BBC News',
            'url' => 'https://newsapi.org/v2/top-headlines?sources=bbc-news',
            'api_key' => env('BBC_NEWS_API_KEY'),
        ],
        'open_news' => [
            'name' => 'Open News',
            'url' => 'https://newsapi.org/v2/top-headlines?sources=open-news',
            'api_key' => env('OPNE_NEWS_API_KEY'),
        ],
    ]
];
