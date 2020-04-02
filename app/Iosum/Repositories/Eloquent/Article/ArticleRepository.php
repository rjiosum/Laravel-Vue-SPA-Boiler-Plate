<?php

namespace App\Iosum\Repositories\Eloquent\Article;


use App\Iosum\Repositories\Interfaces\Article\ArticleRepositoryInterface;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ArticleRepository implements ArticleRepositoryInterface
{
    /**
     * @var Article
     */
    private $article;

    /**
     * @var User
     */
    private $user;

    /**
     * ArticleRepository constructor.
     *
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getArticles()
    {
        if (is_null($this->user)) {
            return $this->article->orderByDesc('id')->paginate(15);
        }
        return $this->user->articles()->orderByDesc('id')->paginate(15);
    }

    /**
     * @param int $articleId
     * @return Article|Article[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getArticleById(int $articleId)
    {
        try {
            return $this->article->findOrFail($articleId);
        } catch (ModelNotFoundException $e) {
            throw $e;
        }
    }

    public function createArticle(array $params)
    {
        $collection = collect($params);
        $merged = $collection->merge(['slug' => '']);

        try {
            DB::beginTransaction();

            $article = $this->user->articles()->create($merged->all());

            $article->slug = Str::slug($article->id . '-' . $article->title);
            $article->save();

            DB::commit();

            return $article;

        } catch (QueryException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function updateArticle(array $params)
    {
        $collection = collect($params);

        $slug = Str::slug($collection->all()['article_id'] . '-' . $collection->all()['title']);

        $merged = $collection->merge(['slug'=>$slug]);

        try {
            DB::beginTransaction();

            $update = $this->article
                ->where('id', $params['article_id'])
                ->update($merged->except('article_id')->all());

            DB::commit();
            return $update;

        } catch (QueryException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }


    /**
     * @param int $articleId
     * @return bool|null
     * @throws \Exception
     */
    public function deleteArticle(int $articleId)
    {
        try {
            DB::beginTransaction();
            $status = $this->article->where('id', $articleId)->delete();
            DB::commit();
            return $status;
        } catch (QueryException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}