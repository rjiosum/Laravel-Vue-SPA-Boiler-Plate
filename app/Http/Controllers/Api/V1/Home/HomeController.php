<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Iosum\Repositories\Interfaces\Article\ArticleRepositoryInterface;
use App\Models\Article;
use Illuminate\Http\Response;


class HomeController extends Controller
{
    /**
     * @var ArticleRepositoryInterface
     */
    private $articleRepository;

    /**
     * Create a new controller instance.
     * @param ArticleRepositoryInterface $articleRepository
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ArticleResource::collection($this->articleRepository->getArticles());
    }

    /**
     * Display the specified resource.
     *
     * @param  Article $article
     * @return Response
     */
    public function show(Article $article)
    {
        return response(new ArticleResource($article), Response::HTTP_OK);
    }
}
