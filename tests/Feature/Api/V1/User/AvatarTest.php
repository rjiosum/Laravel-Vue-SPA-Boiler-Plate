<?php

namespace Tests\Feature\Api\V1\User;

use App\Facades\Helper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $loggedInUser;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->create('User');
        $this->loggedInUser = $this->actingAs($this->user, 'api');
    }

    /** @test */
    public function canUpdateAvatar(): void
    {
        $response = $this->loggedInUser->postJson(route('avatar.update'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        $response->assertStatus(202)
            ->assertJsonStructure([
                'status',
                'file',
                'message'
            ])
            ->assertJson([
                'status' => true,
                'message' => trans('response.success.update', ['attribute' => 'Avatar'])
            ]);

        $file = $response->getOriginalContent()['file'];
        $path = 'avatars/' . Helper::path($this->user->id);

        $this->assertFileExists('storage/app/public/' . $path . $file);

        unlink('storage/app/public/' . $path . $file);

        $this->assertFileNotExists('storage/app/public/' . $path . $file);

    }

    /** @test */
    public function cannotUpdateAvatarWithWrongInput(): void
    {
        //avatar field is required
        $this->loggedInUser->postJson(route('avatar.update'), [
            'avatar' => ''
        ])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'avatar' => [
                        trans('validation.required', ['attribute' => 'avatar'])
                    ]
                ]
            ]);

        //The avatar must be an image.
        $this->loggedInUser->postJson(route('avatar.update'), [
            'avatar' => 'invalid data'
        ])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'avatar' => [
                        trans('validation.image', ['attribute' => 'avatar']),
                        trans('validation.mimes', ['attribute' => 'avatar', 'values' => 'jpeg, png, jpg, gif, svg'])
                    ]
                ]
            ]);

        //The avatar max size is 5MB.
        $this->loggedInUser->postJson(route('avatar.update'), [
            'avatar' => UploadedFile::fake()->create('document.pdf', 6000)
        ])
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'avatar' => [
                        trans('validation.image', ['attribute' => 'avatar']),
                        trans('validation.mimes', ['attribute' => 'avatar', 'values' => 'jpeg, png, jpg, gif, svg']),
                        trans('validation.max.file', ['attribute' => 'avatar', 'max' => 5120])
                    ]
                ]
            ]);
    }
}
