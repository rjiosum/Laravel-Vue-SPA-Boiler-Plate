<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class DuskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Browser::macro('assertVisited', function ($page) {
            if (!$page instanceof Page) {
                $page = new $page;
            }
            return $this->waitForLocation($page->url())
                ->assertPathIs($page->url());
        });

        Browser::macro('typeText', function ($selector = null, $value = null) {
            $this->element($selector)->sendKeys($value);
            return $this;
        });

        Browser::macro('clearInput', function ($selector) {
            $length = strlen($this->value($selector));
            $this->keys($selector, ...array_fill(0, $length, '{backspace}'));
            return $this;
        });

        Browser::macro('jsClick', function ($selector ) {
            $this->script("document.querySelector('$selector').click()");
            return $this;
        });
    }
}
