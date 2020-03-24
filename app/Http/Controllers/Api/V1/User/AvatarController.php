<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Facades\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AvatarUpdateRequest;
use App\Iosum\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class AvatarController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param AvatarUpdateRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(AvatarUpdateRequest $request)
    {
        $user = $request->user('api');
        $uploadedFile = $request->avatar;

        $filename = $this->storeAvatar($uploadedFile, $user);

        $update = $this->userRepository->updateAvatar(['user_id'=>$user->id, 'avatar' => $filename]);

        return response([
            'status' => (bool)$update,
            'file' => $filename,
            'message' => (bool)$update ? trans('response.success.update', ['attribute'=> 'Avatar'])
                : trans('response.error.update', ['attribute'=> 'avatar'])
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * @param $uploadedFile
     * @param $user
     * @return string
     */
    private function storeAvatar(UploadedFile $uploadedFile, User $user): string
    {
        //profile image name
        $filename = Helper::randomKey(8) . '.' . $uploadedFile->getClientOriginalExtension();
        //upload path
        $path = 'avatars/' . Helper::path($user->id); //get sub path 000/000/123
        $disk = 'public';

        //store file
        //$uploadedFile->storeAs($path, $filename, $disk); same as below
        Storage::disk($disk)->putFileAs($path, $uploadedFile, $filename);

        //resize image
        $width = config('laravolt.avatar.width');
        $height = config('laravolt.avatar.height');

        Image::make(public_path('storage/' . $path . $filename))
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })->save();

        //Delete old file
        Storage::disk($disk)->delete(public_path('storage/' . $path . $user->avatar));
        return $filename;
    }
}
