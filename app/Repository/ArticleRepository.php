<?php

namespace App\Repository;

use App\Helpers\ArticleHelper;
use App\Interfaces\ArticleInterface;
use App\Models\Article;
use App\Models\ArticleAuthor;
use App\Models\ArticleCategory;
use App\Models\ArticleSource;
use App\Models\UserAuthors;
use App\Models\UserCategories;
use App\Models\UserSources;

class ArticleRepository implements ArticleInterface {
    public function getArticles($request) {
        $page = $request->page;
        $per_page = $request->per_page;
        $user = request()->user();

        if (1==1 && @$request->is_user_preference == 'true') {
            $authors = UserAuthors::where('user_id', 1)
                ->pluck('author_id')->toArray();
            $sources = UserSources::where('user_id', 1)
                ->pluck('source_id')->toArray();
            $categories = UserCategories::where('user_id', 1)
                ->pluck('category_id')->toArray();
        } else {
            $authors = ArticleHelper::splitString($request->authors);
            $sources = ArticleHelper::splitString($request->sources);
            $categories = ArticleHelper::splitString($request->categories);
        }

        $articles = Article::with(['author', 'source', 'category']);

        if ($authors) {
            $articles->whereIn('author_id', $authors);
        }

        if ($sources) {
            $articles->whereIn('source_id', $sources);
        }

        if ($categories) {
            $articles->whereIn('category_id', $categories);
        }

        if ($request->published_at) {
            $articles->whereDate('published_at', '=',  $request->published_at);
        }

        $articles = $articles->paginate($per_page, ['*'], 'page', $page);

        $data = [
            'articles' => $articles,
            'authors' => $authors,
            'sources' => $sources,
            'categories' => $categories,
        ];

        return $data;
    }

    public function getFilters() {
        $data = [];
        $data['authors'] = ArticleAuthor::all();
        $data['sources'] = ArticleSource::all();
        $data['categories'] = ArticleCategory::all();

        return $data;
    }

    public function saveUserPreferences($request) {
        $user = request()->user();
        $authors = ArticleHelper::splitString($request->authors);
        $sources = ArticleHelper::splitString($request->sources);
        $categories = ArticleHelper::splitString($request->categories);

        $user->authors()->sync($authors);
        $user->sources()->sync($sources);
        $user->categories()->sync($categories);

        return true;
    }
}
