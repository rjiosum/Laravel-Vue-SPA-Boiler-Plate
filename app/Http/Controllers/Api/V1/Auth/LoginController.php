<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Resources\UserPublicResource;
use App\Http\Traits\IssueToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Client;

class LoginController extends Controller
{
    use AuthenticatesUsers, IssueToken;

    protected $maxAttempts = 5; // Default is 5
    protected $decayMinutes = 1; // Default is 1

    private $client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = Client::whereNull('user_id')->where('password_client', 1)->where('revoked', 0)->first();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws ValidationException
     */
    protected function sendLoginResponse(Request $request)
    {
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return response()->json([
                'status' => false,
                'verify' => true,
                'message' => trans('verification.unverified')
            ]);
        }

        $this->clearLoginAttempts($request);
        $response = $this->issueToken($request, 'password');

        if ($response->getStatusCode() != 200) {
            return $this->sendFailedLoginResponse($request);
        }

        $data = json_decode($response->getContent());

        $param = $this->cookieParams($data->access_token);

        if($request->has('remember') && $request->remember){
            $param['minutes'] = 2628000; //five years
        }

        $cookie = Cookie::make($param['name'], $param['value'], $param['minutes'], $param['path'], $param['domain'],
            $param['secure'], $param['httponly'], $param['raw'], $param['samesite']);

        return response()->json([
            'status' => true,
            'message' => trans('auth.login'),
            'user' => new UserPublicResource(Auth::user())
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    /**
     * @param $token
     * @return array
     */
    private function cookieParams($token): array
    {
        return [
            'name' => config('passport.cookie.name'),
            'value' => $token,
            'minutes' => config('passport.cookie.minutes'), //0 means cookie will expires when browser is closed
            'path' => config('passport.cookie.path'),
            'domain' => config('passport.cookie.domain'),
            'secure' => config('passport.cookie.secure'),
            'httponly' => config('passport.cookie.httponly'),
            'raw' => config('passport.cookie.raw'),
            'samesite' => config('passport.cookie.samesite')
        ];
    }
}
