<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the article.
     *
     * @param User $user
     * @param Article $article
     * @return mixed
     */
    public function view(User $user, Article $article)
    {
        return $user->ownsArticle($article);
    }

    /**
     * Determine whether the user can update the article.
     *
     * @param User $user
     * @param Article $article
     * @return mixed
     */
    public function update(User $user, Article $article)
    {
        return $user->ownsArticle($article);
    }

    /**
     * Determine whether the user can delete the article.
     *
     * @param User $user
     * @param Article $article
     * @return mixed
     */
    public function delete(User $user, Article $article)
    {
        return $user->ownsArticle($article);
    }

}
