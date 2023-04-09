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

class NewsApiService implements NewsInterface {

    public function fetchNews(): array
    {
        $result = [];
        $articles = [];
        $source = config('news.sources.news_api');
        $articleCategories = ArticleCategory::all();
        $lastFetchedDate = ArticleAPILastFetchDate::where("name", 'news_api')->first();

        if (!empty($articleCategories)) {
            foreach ($articleCategories as $category) {
                $response = Http::get($source['url'], [
                    'apiKey' => $source['api_key'],
                    'category' => $category->name,
                    'published_date' => @$lastFetchedDate->last_published_date ?? null
                ]);
                $data = json_decode($response->getBody(), true);
                if (array_key_exists('articles', $data)) {
                    $result[$category->id] = $data['articles'];
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

                    if (@$article['author'] && !in_array($article['author'], $authors)) {
                        $articleAuthor = ArticleAuthor::firstOrCreate([
                            'name' => $article['author']
                        ]);

                        $authors[$articleAuthor->name] = $articleAuthor->id;
                    }

                    if (@$article['source']['name'] && !in_array($article['source']['name'], $sources)) {
                        $articleSource = ArticleSource::firstOrCreate([
                            'name' => $article['source']['name'] ?? 'NewsApi'
                        ]);

                        $sources[$articleSource->name] = $articleSource->id;
                    }

                    $filteredArticles[] = [
                        'title' => @$article['title'],
                        'image' => @$article['urlToImage'],
                        'url' => @$article['url'],
                        'category_id' => $articleCategoryID,
                        'source_id' => !empty($articleSource) ? $articleSource->id : @$sources[$articleAuthor->name],
                        'author_id' => !empty($articleAuthor) ? $articleAuthor->id : @$authors[$article['author']],
                        'description' => @$article['description'],
                        'published_at' => Carbon::parse(@$article['publishedAt'])->format('Y-m-d H:i:s')
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
            'name' => 'news_api'
        ], [
            'last_published_date' => Carbon::now()->format('Y-d-m h:i:s')
        ]);

        return $articles;
    }
}
