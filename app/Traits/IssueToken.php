<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

trait IssueToken
{
    /**
     * @param Request $request
     * @param $grantType
     * @param string $scope
     * @return mixed
     */
    public function issueToken(Request $request, $grantType, $scope = "")
    {
        $params = [
            'grant_type' => $grantType,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => $scope
        ];
        if ($grantType !== 'social') {
            $params['username'] = $request->username ?: $request->email;
        }
        $request->request->add($params);
        $proxy = Request::create(config('passport.url'), 'POST');
        return Route::dispatch($proxy);
    }
}