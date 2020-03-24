<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationResendRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;



class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return Response
     *
     */
    public function verify(Request $request)
    {
        $user = User::where('id', $request->route('id'))->first();

        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'message' => trans('verification.user')
            ], Response::HTTP_BAD_REQUEST);
        }

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            return response()->json([
                'status' => false,
                'message' => trans('verification.invalid')
            ], Response::HTTP_BAD_REQUEST);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return response()->json([
                'status' => false,
                'message' => trans('verification.invalid')
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => true,
                'message' => trans('verification.already_verified')
            ], Response::HTTP_OK);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'status' => true,
            'message' => trans('verification.verified')
        ], Response::HTTP_OK);
    }

    /**
     * Resend the email verification notification.
     *
     * @param VerificationResendRequest $request
     * @return Response
     * @throws ValidationException
     */
    public function resend(VerificationResendRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.user')],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.already_verified')],
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'status' => true,
            'message' => trans('verification.sent')
        ], Response::HTTP_OK);
    }
}
