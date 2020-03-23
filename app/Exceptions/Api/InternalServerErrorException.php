<?php


namespace App\Exceptions\Api;


use Illuminate\Http\Response;

class InternalServerErrorException implements ExceptionResponseInterface
{
    use PrepareException;

    /**
     * @param $exception
     * @return mixed
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function response($exception)
    {
        /*$response = [];
        if(config('app.debug')){
            $response = $this->responseArray($exception);
        }
        $response['message'] = trans('response.exception.internal');*/

        $message = trans('response.exception.internal');

        if (config('app.debug')) {
            $message .= $this->responseHTML($exception);
        }

        $response['message'] = $message;

        return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}