<?php

namespace App\Console\Commands;

use App\Services\NewsApiService;
use App\Services\NewYorkTimesApiService;
use App\Services\TheGuardiansApiService;
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
    protected $description = 'Fetch the news from available resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sources = config('news.sources');

        if (!empty($sources)) {
            foreach ($sources as $key => $source) {
                $this->info("Fetching " . $source['name'] . " Articles...");

                if ($key != 'news_api') {
                    sleep(60);
                }

                if ($key == 'news_api') {
                    app()->call(NewsApiService::class . '@fetchNews');
                } elseif ($key == 'newyork_times') {
                    app()->call(NewYorkTimesApiService::class . '@fetchNews');
                } elseif ($key == 'the_guardians') {
                    app()->call(TheGuardiansApiService::class . '@fetchNews');
                }

                $this->info("Finished " . $source['name'] . " Articles");
                $this->info("");
            }
        }
    }
}
