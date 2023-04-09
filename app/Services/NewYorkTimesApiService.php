<?php

namespace App\Services;

use App\Interfaces\NewsInterface;
use App\Models\Article;
use App\Models\ArticleAPILastFetchDate;
use App\Models\ArticleAuthor;
use App\Models\ArticleCategory;
use App\Models\ArticleSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class NewYorkTimesApiService implements NewsInterface {

    public function fetchNews(): array
    {
        $result = [];
        $articles = [];
        $source =  config('news.sources.newyork_times');
        $articleCategories = ArticleCategory::all();
        $lastFetchedDate = ArticleAPILastFetchDate::where("name", 'new_york')->first();

        if (!empty($articleCategories)) {
            foreach ($articleCategories as $category) {
                $response = Http::get($source['url'] . '/search/v2/articlesearch.json', [
                    'fq' => 'news_desk:(' . $category->name . ')',
                    'sort' => 'newest',
                    'api-key' => $source['api_key'],
                    'page_size' => 20,
                    'begin_date' => @$lastFetchedDate->last_published_date ?
                        str_replace("-", "", $lastFetchedDate->last_published_date) : null
                ]);
                $data = json_decode($response->getBody(), true);
                if (array_key_exists('response', $data)) {
                    $result[$category->id] = $data['response']['docs'];
                }
            }

            $filteredArticles = $this->parseNews($result);
            $articles = $this->store($filteredArticles);
        }

        return $articles;
    }

    public function parseNews($result): array
    {
        $authors = [];
        $sources = [];
        $articleAuthor = [];
        $articleSource = [];
        $filteredArticles = [];

        if (!empty($result)) {
            foreach ($result as $articleCategoryID => $articles) {
                foreach ($articles as $article) {
                    $author =
                        @$article['byline']['person']['firstname'] . " " . @$article['byline']['person']['lastname'];
                    if (@$author && !in_array($author, $authors)) {
                        $articleAuthor = ArticleAuthor::firstOrCreate([
                            'name' => $author
                        ]);

                        $authors[$articleAuthor->name] = $articleAuthor->id;
                    }

                    if (@$article['source'] && !in_array($article['source'], $sources)) {
                        $articleSource = ArticleSource::firstOrCreate([
                            'name' => $article['source'] ?? 'NewYork'
                        ]);

                        $sources[$articleSource->name] = $articleSource->id;
                    }

                    $filteredArticles[] = [
                        'title' => @$article['headline']['main'],
                        'image' => @$article['multimedia'][0]['url'],
                        'url' => @$article['web_url'],
                        'category_id' => $articleCategoryID,
                        'source_id' => !empty($articleSource) ? $articleSource->id : @$sources[$articleAuthor->name],
                        'author_id' => !empty($articleAuthor) ? $articleAuthor->id : @$authors[$author],
                        'description' => @$article['lead_paragraph'],
                        'published_at' => Carbon::parse(@$article['pub_date'])->format('Y-m-d H:i:s')
                    ];
                }
            }
        }

        return $filteredArticles;
    }

    public function store($articles): array
    {
        Article::insert($articles);
        ArticleAPILastFetchDate::updateOrCreate([
            'name' => 'new_york'
        ], [
            'last_published_date' => Carbon::now()->format('Y-d-m h:i:s')
        ]);

        return $articles;
    }
}
