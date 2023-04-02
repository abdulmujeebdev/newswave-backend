<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Fetch the news from availabe resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    }

    public function process()
    {
        // Fetch news articles from each source in batches
        foreach ($newsSources as $source) {
            $currentPage = 1;
            $pageSize = 100;
            $totalResults = $pageSize;

            while ($totalResults > 0) {
                // Fetch a batch of news articles from the API
                $response = Http::get($source['url'] . "&page=$currentPage&pageSize=$pageSize&apiKey=" . $source['apiKey']);
                $articles = $response->json()['articles'];

                // Process the batch of news articles
                foreach ($articles as $article) {
                    // Save the news article to your local database
                    News::create([
                        'source' => $source['name'],
                        'title' => $article['title'],
                        'description' => $article['description'],
                        'url' => $article['url'],
                        'published_at' => $article['publishedAt'],
                    ]);
                }

                // Update the pagination variables
                $currentPage++;
                $totalResults = count($articles);
            }
        }
    }
}
