<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ArticleHelper;
use App\Models\Article;
use App\Models\ArticleAuthor;
use App\Models\ArticleCategory;
use App\Models\ArticleSource;
use App\Models\UserAuthors;
use App\Models\UserCategories;
use App\Models\UserSources;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticlesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user && @$request->is_user_preference == 'true') {
            $authors = UserAuthors::where('user_id', $user->id)
                ->pluck('author_id')->toArray();
            $sources = UserSources::where('user_id', $user->id)
                ->pluck('source_id')->toArray();
            $categories = UserCategories::where('user_id', $user->id)
                ->pluck('category_id')->toArray();
        } else {
            $authors = ArticleHelper::splitString($request->authors);
            $sources = ArticleHelper::splitString($request->sources);
            $categories = ArticleHelper::splitString($request->categories);
        }

        $articles = Article::with(['author', 'source', 'category']);

        if (!empty($authors)) {
            $articles->whereIn('author_id', $authors);
        }

        if (!empty($sources)) {
            $articles->whereIn('source_id', $sources);
        }

        if (!empty($categories)) {
            $articles->whereIn('category_id', $categories);
        }

        if ($request->published_at) {
            $articles->where('published_at', $request->published_at);
        }

        $articles = $articles->get();

        return response()->json([
            'success' => true,
            'articles' => $articles,
        ], Response::HTTP_OK);
    }
    public function getAuthors() {
        $user = auth()->user();

        $authors = ArticleAuthor::with(['userAuthors' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->get();

        return response()->json([
            'success' => true,
            'authors' => $authors,
        ], Response::HTTP_OK);
    }

    public function getSources() {
        $user = auth()->user();

        $sources = ArticleSource::with(['userSources' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->get();

        return response()->json([
            'success' => true,
            'authors' => $sources,
        ], Response::HTTP_OK);
    }

    public function getCategories() {
        $user = auth()->user();

        $categories = ArticleCategory::with(['userCategories' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->get();

        return response()->json([
            'success' => true,
            'authors' => $categories,
        ], Response::HTTP_OK);
    }

    public function saveUserPreferences(Request $request) {

        $user = auth()->user();
        $authors = ArticleHelper::splitString($request->authors);
        $sources = ArticleHelper::splitString($request->sources);
        $categories = ArticleHelper::splitString($request->categories);

        $user->authors()->sync($authors);
        $user->sources()->sync($sources);
        $user->categories()->sync($categories);

        return response()->json([
            'success' => true,
            'authors' => 'Preferences Updated Successfully',
        ], Response::HTTP_OK);
    }
}
