<?php

namespace Tests\Browser\PagesTests\Article;

use App\Models\Article;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Article\ArticleLists;
use Tests\Browser\PagesTests\Authenticate;
use Tests\DuskTestCase;

class DeleteArticleTest extends DuskTestCase
{
    use Authenticate;

    private $user;
    private $article;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->user = User::find(1);
        $this->article = Article::orderByDesc('id')->first();
    }

    /** @test */
    public function canDeleteAnArticle(): void
    {
        $this->browse(function (Browser $browser) {
            $this->auth($browser)
                ->assertSeeLink('Articles')
                ->clickLink('Articles')
                ->pause(4000)
                ->on(new ArticleLists)
                ->with('.article-lists', function ($list) {
                    $list
                        ->waitForText($this->article->title)
                        ->assertSee($this->article->title)
                        ->jsClick('#' . $this->article->uuid);
                })
                ->pause(100)
                ->press('Yes')
                ->pause(5000)
                ->assertDontSee($this->article->title);
        });
    }
}
