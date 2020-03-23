<?php


namespace App\Exceptions\Api;


use Illuminate\Http\Response;

class NotFoundHttpException implements ExceptionResponseInterface
{
    use PrepareException;

    /**
     * @param $exception
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response|mixed
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function response($exception)
    {
        $message = trans('response.exception.http');

        if (config('app.debug')) {
            $message .= $this->responseHTML($exception);
        }

        $response['message'] = $message;

        return response($response, Response::HTTP_NOT_FOUND);
    }
}