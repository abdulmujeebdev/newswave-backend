<?php

namespace App\Repository;

use App\Helpers\ArticleHelper;
use App\Interfaces\ArticleInterface;
use App\Models\Article;
use App\Models\ArticleAuthor;
use App\Models\ArticleCategory;
use App\Models\ArticleSource;
use App\Models\User;
use App\Models\UserAuthors;
use App\Models\UserCategories;
use App\Models\UserSources;
use Illuminate\Http\Request;

class ArticleRepository implements ArticleInterface {
    public function getArticles($request) {
        $page = $request->page;
        $per_page = $request->per_page;
        $user = request()->user();

        if (1==1 && @$request->is_user_preference == 'true') {
            $authors = UserAuthors::where('user_id', 1)
                ->pluck('author_id')->toArray();
            $sources = UserSources::where('user_id',  1)
                ->pluck('source_id')->toArray();
            $categories = UserCategories::where('user_id',  1)
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

        $authors = array_filter($request->authors, function($value) {
            return !empty($value);
        });

        $categories = array_filter($request->categories, function($value) {
            return !empty($value);
        });

        $sources = array_filter($request->sources, function($value) {
            return !empty($value);
        });
//        $user = request()->user();
        $user = User::find(1);

        $user->authors()->sync(array_keys($authors));
        $user->sources()->sync(array_keys($sources));
        $user->categories()->sync(array_keys($categories));

        return true;
    }

    public function getUserPreferences($request) {
        //        $user = request()->user();
        $authors = UserAuthors::where('user_id', 1)->pluck('author_id')->toArray();
        $categories = UserCategories::where('user_id', 1)->pluck('category_id')->toArray();
        $sources = UserSources::where('user_id', 1)->pluck('source_id')->toArray();

        $data = [
            'authors' => $authors,
            'categories' => $categories,
            'sources' => $sources,
        ];

        return $data;

    }
}
