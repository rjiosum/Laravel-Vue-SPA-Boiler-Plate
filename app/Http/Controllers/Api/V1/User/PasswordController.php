<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordUpdateRequest;
use App\Iosum\Repositories\Interfaces\User\UserRepositoryInterface;
use Illuminate\Http\Response;


class PasswordController extends Controller
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
     * @param PasswordUpdateRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(PasswordUpdateRequest $request)
    {
        $user = $request->user('api');
        $request->merge(['user_id'=>$user->id]);

        $update = $this->userRepository->updatePassword($request->all());

        return response([
            'status' => (bool)$update,
            'message' => (bool)$update ? trans('response.success.update', ['attribute'=> 'Password'])
                : trans('response.error.update', ['attribute'=> 'password'])
        ], Response::HTTP_ACCEPTED);
    }
}
