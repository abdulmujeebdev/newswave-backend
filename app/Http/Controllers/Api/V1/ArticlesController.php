<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ArticleAuthor;
use App\Models\ArticleCategory;
use App\Models\ArticleSource;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function index()
    {
        return true;
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

        $user->authors()->sync($request->get('authors'));
        $user->sources()->sync($request->get('sources'));
        $user->categories()->sync($request->get('categories'));

        return response()->json([
            'success' => true,
            'authors' => 'Preferences Updated Successfully',
        ], Response::HTTP_OK);
    }
}
