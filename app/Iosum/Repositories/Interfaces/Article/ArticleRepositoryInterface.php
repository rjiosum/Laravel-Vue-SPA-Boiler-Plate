<?php


namespace App\Iosum\Repositories\Interfaces\Article;


interface ArticleRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getArticles();

    /**
     * @param int $articleId
     * @return mixed
     */
    public function getArticleById(int $articleId);

    /**
     * @param array $params
     * @return mixed
     */
    public function createArticle(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function updateArticle(array $params);

    /**
     * @param int $articleId
     * @return mixed
     */
    public function deleteArticle(int $articleId);

}