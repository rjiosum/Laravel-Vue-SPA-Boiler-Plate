<?php

namespace App\Exceptions\Api;

use ReflectionClass;
use ReflectionMethod;

trait PrepareException
{

    /**
     * @param $exception
     * @return array
     * @throws \ReflectionException
     */
    public function responseArray($exception): array
    {
        $response = [];

        $reflection = new ReflectionClass('App\Exceptions\Api\PrepareException');
        $methods = $reflection->getMethods(ReflectionMethod::IS_PRIVATE);

        foreach ($methods as $method) {
            $key = str_replace('has', '', $method->name);

            $value = call_user_func_array([$this, $method->name], [$exception]);

            if (!is_null($value) && $value !== "") {
                $response[$key] = $value;
            }
        }

        return $response;
    }

    /**
     * @param $exception
     * @return string
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function responseHTML($exception): string
    {
        $message = '';
        $response = $this->responseArray($exception);

        if (!empty($response)) {
            $message .= view('exception.response', ['response' => $response])->render();
        }

        return $message;
    }

    /**
     * @param $exception
     * @return string
     */
    private function hasOriginalMessage($exception)
    {
        return (method_exists($exception, 'getMessage')) ? $exception->getMessage() : null;
    }

    /**
     * @param $exception
     * @return string
     */
    private function hasStatusCode($exception)
    {
        return (method_exists($exception, 'getStatusCode')) ? $exception->getStatusCode() : 500;
    }

    /**
     * @param $exception
     * @return string
     */
    private function hasFile($exception)
    {
        return (method_exists($exception, 'getFile')) ? $exception->getFile() : null;
    }

    /**
     * @param $exception
     * @return string
     */
    private function hasLine($exception)
    {
        return (method_exists($exception, 'getLine')) ? $exception->getLine() : null;
    }

    /**
     * @param $exception
     * @return string
     */
    private function hasCode($exception)
    {
        return (method_exists($exception, 'getCode')) ? $exception->getCode() : null;
    }

    /**
     * @param $exception
     * @return string
     */
    private function hasSeverity($exception)
    {
        return (method_exists($exception, 'getSeverity')) ? $exception->getSeverity() : null;
    }

    /**
     * @param $exception
     * @return string
     */
    private function hasTrace($exception)
    {
        return (method_exists($exception, 'getTraceAsString')) ? $exception->getTraceAsString() : null;
    }

    /**
     * @param $exception
     * @return string
     */
    private function hasPrevious($exception)
    {
        return (method_exists($exception, 'getPrevious')) ? $exception->getPrevious() : null;
    }

}
