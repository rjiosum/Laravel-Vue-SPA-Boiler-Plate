<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Iosum\Repositories\Interfaces\Article\ArticleRepositoryInterface;
use App\Models\Article;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    /**
     * @var ArticleRepositoryInterface
     */
    private $articleRepository;

    /**
     * Create a new controller instance.
     *
     * @param ArticleRepositoryInterface $articleRepository
     */
    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;

        $this->middleware(function ($request, $next) {
            $this->articleRepository->setUser(auth()->guard('api')->user());
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ArticleResource::collection($this->articleRepository->getArticles());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArticleStoreRequest $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(ArticleStoreRequest $request)
    {
        $article = $this->articleRepository->createArticle($request->all());

        return response([
            'status' => (bool)$article,
            'data' => new ArticleResource($article),
            'message' => (bool)$article ? trans('response.success.create', ['attribute'=> 'Article'])
                : trans('response.error.create', ['attribute'=> 'article'])
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Display the specified resource.
     *
     * @param  Article $article
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Article $article)
    {
        $this->authorize('view', $article);

        return response(new ArticleResource($article), Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param ArticleUpdateRequest $request
     * @param  Article $article
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        $this->authorize('update', $article);

        $request->merge(['article_id' => $article->id]);

        $update = $this->articleRepository->updateArticle($request->all());

        return response([
            'status' => (bool)$update,
            'data' => new ArticleResource($this->articleRepository->getArticleById($article->id)),
            'message' => (bool)$update ? trans('response.success.update', ['attribute'=> 'Article'])
                : trans('response.error.update', ['attribute'=> 'article'])
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article $article
     * @return Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $status = $this->articleRepository->deleteArticle($article->id);

        return response([
            'status' => (bool)$status,
            'data' => [],
            'message' => (bool)$status ? trans('response.success.delete', ['attribute'=> 'Article'])
                : trans('response.error.delete', ['attribute'=> 'article'])
        ], Response::HTTP_OK);

    }
}
