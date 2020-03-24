<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;


class LogoutController extends Controller
{
    /**
     * Logout user
     *
     * @param Request $request
     * @return $this
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $cookie = Cookie::forget(config('passport.cookie.name'));

        return response([
            'status' => true,
            'message' => trans('auth.logout')
        ], Response::HTTP_OK)->withCookie($cookie);
    }
}
