<?php

namespace App\Services;

use App\Interfaces\NewsInterface;
use App\Models\Article;
use App\Models\ArticleAuthor;
use App\Models\ArticleCategory;
use App\Models\ArticleSource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class TheGuardiansApiService implements NewsInterface {

    public function fetchNews(): array
    {
        $result = [];
        $articles = [];
        $source =  config('news.sources.the_guardians');
        $articleCategories = ArticleCategory::all();

        if (!empty($articleCategories)) {
            foreach ($articleCategories as $category) {
                $response = Http::get($source['url'] . '/search', [
                    'show-fields' => 'thumbnail,bodyText',
                    'show-tags' => 'contributor',
                    'q' => $category,
                    'api-key' => $source['api_key'],
                    'page' => 1,
                    'page-size' => 20,
                ]);
                $data = json_decode($response->getBody(), true);

                if (array_key_exists('response', $data)) {
                    $result[$category->id] = $data['response']['results'];
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

                    if (@$article['tags'][0]['webTitle'] && !in_array($article['tags'][0]['webTitle'], $authors)) {
                        $articleAuthor = ArticleAuthor::firstOrCreate([
                            'name' => $article['tags'][0]['webTitle']
                        ]);

                        $authors[$articleAuthor->name] = $articleAuthor->id;
                    }

                    if (!in_array('The Guardians', $sources)) {
                        $articleSource = ArticleSource::firstOrCreate([
                            'name' => 'The Guardians'
                        ]);

                        $sources[$articleSource->name] = $articleSource->id;
                    }

                    $filteredArticles[] = [
                        'title' => @$article['webTitle'],
                        'image' => @$article['fields']['thumbnail'],
                        'url' => @$article['webUrl'],
                        'category_id' => $articleCategoryID,
                        'source_id' => !empty($articleSource) ? $articleSource->id : @$sources['The Guardians'],
                        'author_id' =>
                            !empty($articleAuthor) ? $articleAuthor->id : @$authors[$article['tags'][0]['webTitle']],
                        'description' => @$article['fields']['bodyText'],
                        'published_at' => Carbon::parse(@$article['webPublicationDate'])->format('Y-m-d H:i:s')
                    ];
                }
            }
        }

        return $filteredArticles;
    }

    public function store($articles): array
    {
        Article::insert($articles);
        return $articles;
    }
}
