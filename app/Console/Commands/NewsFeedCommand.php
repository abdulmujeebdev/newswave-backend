<?php

namespace App\Console\Commands;

use App\Services\NewsApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class NewsFeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the news from available resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app()->call(NewsApiService::class . '@fetchNews');

        // Newyork times
//         $source = config('news.sources.newyork_times');
//         $response = Http::get($source['url'] . '/search/v2/articlesearch.json', [
//             'fq' => 'news_desk:(Sports)',
//             'sort' => 'newest',
//             'api-key' => $source['api_key'],
//             'page_size' => 100,
//         ]);
//        $data = json_decode($response->getBody(), true);
        //the Guardians
//         $source = config('news.sources.the_guardians');
//         $response = Http::get($source['url'] . '/search', [
//             'show-fields' => 'thumbnail',
//             'show-tags' => 'contributor',
//             'q' => 'science',
//             'api-key' => $source['api_key'],
//             'page' => 1,
//             'page-size' => 100,
//         ]);
    }
}
