<?php

namespace Tests\Feature\Api\V1\Article;

use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $user, $loggedInUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create('User');
        $this->loggedInUser = $this->ActingAs($this->user, 'api');
    }

    /** @test */
    public function canListCollectionOfPaginatedArticleResults(): void
    {
        $this->create('Article', 20);

        $this->loggedInUser->getJson(route('article'))
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
    public function will_throw_404_error_if_article_we_are_trying_to_fetch_does_not_exists(): void
    {
        $this->loggedInUser->getJson(route('article.show', -1))
            ->assertStatus(404);
    }

    /** @test */
    public function canShowAnArticle(): void
    {
        $article = $this->create('Article');
        $this->loggedInUser->getJson(route('article.show', ['article' => $article]))
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

    /** @test */
    public function willThrowValidationErrorWhileCreatingAnArticleWithWrongInput(): void
    {
        //title field is required
        $this->loggedInUser->postJson(route('article.store'), $this->data(['title' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => [
                        trans('validation.required', ['attribute' => 'title'])
                    ]
                ]
            ]);

        //title can have max 150 character
        $this->loggedInUser->postJson(route('article.store'), $this->data(['title' => Str::random(200)]))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => [
                        trans('validation.max.string', ['attribute' => 'title', 'max' => 150])
                    ]
                ]
            ]);

        //description field is required
        $this->loggedInUser->postJson(route('article.store'), $this->data(['description' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'description' => [
                        trans('validation.required', ['attribute' => 'description'])
                    ]
                ]
            ]);

        //status field is required
        $this->loggedInUser->postJson(route('article.store'), $this->data(['status' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'status' => [
                        trans('validation.required', ['attribute' => 'status'])
                    ]
                ]
            ]);
    }

    /** @test */
    public function canStoreAnArticle(): void
    {
        $this->loggedInUser->postJson(route('article.store'), $data = $this->data())
            ->assertStatus(202)
            ->assertJsonStructure([
                "status",
                "data" => [
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
                ],
                "message"
            ])
            ->assertJson([
                "status" => true,
                "data" => [
                    "title" => $data['title'],
                    "description" => $data['description'],
                    "status" => $data['status']
                ],
                "message" => trans('response.success.create', ['attribute'=> 'Article'])
            ]);
        $this->assertDatabaseHas('articles', [
            "title" => $data["title"],
            "status" => $data["status"],
        ]);
    }

    /** @test */
    public function willThrowValidationErrorWhileUpdatingAnArticleWithWrongInput(): void
    {
        $article = $this->create('Article');

        //title field is required
        $this->loggedInUser->patchJson(route('article.update', ['article' => $article]), $this->data(['title' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => [
                        trans('validation.required', ['attribute' => 'title'])
                    ]
                ]
            ]);

        //title can have max 150 character
        $this->loggedInUser->patchJson(route('article.update', ['article' => $article]), $this->data(['title' => Str::random(200)]))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'title' => [
                        trans('validation.max.string', ['attribute' => 'title', 'max' => 150])
                    ]
                ]
            ]);

        //description field is required
        $this->loggedInUser->patchJson(route('article.update', ['article' => $article]), $this->data(['description' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'description' => [
                        trans('validation.required', ['attribute' => 'description'])
                    ]
                ]
            ]);
        //status field is required
        $this->loggedInUser->patchJson(route('article.update', ['article' => $article]), $this->data(['status' => '']))
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'status' => [
                        trans('validation.required', ['attribute' => 'status'])
                    ]
                ]
            ]);
    }

    /** @test */
    public function willThrow404IfAnArticleWeWantToUpdateIsNotFound(): void
    {
        $this->loggedInUser->patchJson(route('article.update', ['article' => -1]), $this->data())
            ->assertStatus(404);
    }

    /** @test */
    public function canUpdateAnArticle(): void
    {
        $article = $this->create('Article');

        $this->loggedInUser->patchJson(route('article.update', ['article' => $article]),
            $data = $this->data(['title' => 'This is a new title']))
             ->assertJson([
                'status' => true,
                'data' => [
                    'title' => $data['title'],
                    'status' => $data['status'],
                ],
                'message' => trans('response.success.update', ['attribute'=> 'Article'])
            ])
            ->assertJsonStructure([
                'status',
                'data' => [
                    'title',
                    'description',
                    'status'
                ],
                'message'
            ])
            ->assertStatus(202);

        $this->assertDatabaseHas('articles', [
            'title' => $data['title'],
            'status' => $data['status']
        ]);
    }

    /** @test */
    public function willThrow404ErrorIfAnArticleWeAreTryingToDeleteDoesNotExists(): void
    {
        $this->loggedInUser->deleteJson(route('article.destroy', -1))
            ->assertStatus(404);
    }

    /** @test */
    public function canDeleteAnArticle(): void
    {
        $article = $this->create('Article');

        $this->loggedInUser->deleteJson(route('article.destroy', ['article' => $article]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'status', 'message'
            ])->assertJson([
                'status' => true,
                'message' => trans('response.success.delete', ['attribute'=> 'Article'])
            ]);
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    private function data($data = []): array
    {
        return [
            'title' => $data['title'] ?? $this->faker->unique()->sentence,
            'description' => $data['description'] ?? $this->faker->realText(1000),
            'status' => $data['status'] ?? 1,
        ];
    }
}
