<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Log;
use JWTAuth;
use App\Interfaces\ArticleInterface;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticlesController extends Controller
{
    /**
     * @var ArticleInterface
     */
    protected $articleInterface;

    public function __construct(ArticleInterface $articleInterface)
    {
        $this->articleInterface = $articleInterface;
    }

    public function index(Request $request)
    {
        $data = $this->articleInterface->getArticles($request);

        return response()->json([
            'success' => true,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function getFilters(Request $request)
    {
        $data = $this->articleInterface->getFilters($request);

        return response()->json($data, Response::HTTP_OK);
    }

    public function saveUserPreferences(Request $request)
    {
        $this->articleInterface->saveUserPreferences($request);

        return response()->json([
            'success' => true,
            'message' => 'Preferences Updated Successfully',
        ], Response::HTTP_OK);
    }

    public function getUserPreferences(Request $request)
    {
        $data = $this->articleInterface->getUserPreferences($request);

        return response()->json($data, Response::HTTP_OK);
    }
}
