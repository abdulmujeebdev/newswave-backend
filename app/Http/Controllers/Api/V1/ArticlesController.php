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
        $user = request()->user();

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
            $articles->where('published_at', $request->published_at);
        }

        $articles = $articles->get();

        return response()->json([
            'success' => true,
            'articles' => $articles,
        ], Response::HTTP_OK);
    }
    public function getFilters()
    {
        $data = [];
        $data['authors'] = ArticleAuthor::paginate();
        $data['sources'] = ArticleSource::all();
        $data['categories'] = ArticleCategory::all();

        return response()->json($data, Response::HTTP_OK);
    }

    public function saveUserPreferences(Request $request)
    {
        $user = request()->user();
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
