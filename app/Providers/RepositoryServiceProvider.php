<?php

namespace App\Providers;

use App\Iosum\Repositories\Eloquent\Article\ArticleRepository;
use App\Iosum\Repositories\Eloquent\User\UserRepository;
use App\Iosum\Repositories\Interfaces\Article\ArticleRepositoryInterface;
use App\Iosum\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        UserRepositoryInterface::class => UserRepository::class,
        ArticleRepositoryInterface::class => ArticleRepository::class
    ];
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->repositories as $interface => $implementation)
        {
            $this->app->bind($interface, $implementation);
        }

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
