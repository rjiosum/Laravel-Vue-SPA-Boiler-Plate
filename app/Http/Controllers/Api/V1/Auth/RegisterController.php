<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Facades\Helper;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravolt\Avatar\Facade as Avatar;

class RegisterController extends Controller
{
    /**
     * @var Avatar
     */
    private $avatar;

    /**
     * Create a new controller instance.
     *
     * @param Avatar $avatar
     */
    public function __construct(Avatar $avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        if ($request->has('avatar') && $request->avatar) {
            $this->createAvatar($user);
        }

        event(new Registered($user));

        return $this->registered($request, $user)
            ?: (new LoginController())->login($request);
    }

    protected function registered(Request $request, $user)
    {
        if ($user instanceof MustVerifyEmail) {
            return response([
                'status' => true,
                'verify' => true,
                'message' => trans('verification.sent')
            ], Response::HTTP_OK);
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    private function createAvatar(User $user)
    {
        $avatar = $this->avatar::create($user->first_name . ' ' . $user->last_name)->getImageObject()->encode('png');
        $avatarName = Helper::randomKey(8) . '.png';
        Storage::put('avatars/' . Helper::path($user->id) . $avatarName, (string)$avatar);

        return $user->update(['avatar' => $avatarName]);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
