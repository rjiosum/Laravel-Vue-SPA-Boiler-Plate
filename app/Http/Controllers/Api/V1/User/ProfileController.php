<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileUpdateRequest;
use App\Http\Resources\UserPublicResource;
use App\Iosum\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends Controller
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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function index(Request $request)
    {
        if(!$request->user('api')){
            return response([], Response::HTTP_PRECONDITION_FAILED);
        }
        return response(['user' => new UserPublicResource($request->user('api'))], Response::HTTP_OK);
    }

    /**
     * @param ProfileUpdateRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user('api');
        $request->merge(['user_id'=>$user->id]);

        $update = $this->userRepository->updateProfile($request->all());

        return response([
            'status' => (bool)$update,
            'message' => (bool)$update ? trans('response.success.update', ['attribute'=> 'Profile'])
                : trans('response.error.update', ['attribute'=> 'profile'])
        ], Response::HTTP_ACCEPTED);
    }

}
