<?php

namespace Tests\Browser\PagesTests\Article;

use App\Models\Article;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Article\ArticleLists;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class ArticleListsTest extends DuskTestCase
{
    use Authenticate;

    private $user;
    private $article;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->user = User::find(1);
        $this->article = Article::orderByDesc('id')->first();
    }

    /** @test */
    public function canShowListOfPaginatedResult()
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->assertSeeLink('Articles')
                ->clickLink('Articles')
                ->pause(5000)
                ->on(new ArticleLists)
                ->assertTitle('Articles')
                ->waitForText($this->article->title)
                ->assertSeeLink('Create Article')
                ->assertSee($this->article->title);
        });
    }
}
