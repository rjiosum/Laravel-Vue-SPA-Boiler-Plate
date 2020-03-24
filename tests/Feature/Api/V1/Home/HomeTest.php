<?php

namespace Tests\Feature\Api\V1\Home;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create('User');
    }

    /** @test */
    public function canListPaginatedArticleResultsWithoutUserLoggedIn(): void
    {
        $this->create('Article', 20);

        $this->getJson(route('home'))
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "*" => [
                        "uuid",
                        "title",
                        "slug",
                        "description",
                        "status",
                        "user" => [
                            "id",
                            "uuid",
                            "name",
                            "email",
                            "avatar",
                        ],
                        "createdAt",
                        "updatedAt",
                        "created",
                        "updated",
                    ]
                ],
                "links" => ["first", "last", "prev", "next"],
                "meta" => [
                    "current_page", "last_page", "from", "to",
                    "path", "per_page", "total"
                ]
            ]);
    }

    /** @test */
    public function willThrow404ErrorIfArticleWeAreTryingToFetchDoesNotExistsWithoutUserLoggedIn(): void
    {
        $this->getJson(route('home.show', -1))
            ->assertStatus(404);
    }

    /** @test */
    public function canShowAnArticleWithoutUserLoggedIn(): void
    {
        $article = $this->create('Article');
        $this->getJson(route('home.show', ['article' => $article]))
            ->assertJson([
                "uuid" => $article->uuid,
                "title" => $article->title,
                "slug" => $article->slug,
                "description" => $article->body,
                "status" => $article->status,
                "user" => [
                    "uuid" => $this->user->uuid,
                    "name" => $this->user->full_name,
                    "email" => $this->user->email,
                ]
            ])
            ->assertStatus(200);
    }
}
