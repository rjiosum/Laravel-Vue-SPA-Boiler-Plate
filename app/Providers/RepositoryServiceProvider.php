<?php

namespace App\Providers;

use App\Iosum\Repositories\Eloquent\User\ArticleRepository;
use App\Iosum\Repositories\Eloquent\User\UserRepository;
use App\Iosum\Repositories\Interfaces\User\ArticleRepositoryInterface;
use App\Iosum\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        UserRepositoryInterface::class => UserRepository::class,
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
