<?php

namespace App\Exceptions;


use App\Exceptions\Api\InternalServerErrorException;
use ErrorException;

trait ApiHandler
{
    /**
     * Get response from the thrown exception
     *
     * @param $request
     * @param $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function resolve($request, $exception)
    {
        if ($exception instanceof ErrorException) {
            return (new InternalServerErrorException)->response($exception);
        }

        $className = $this->name($exception);

        if($this->exists($className)){
            $class = self::NAMESPACE . $className;
            return (new $class)->response($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Get the name of the class
     *
     * @param $exception
     * @return string
     */
    private function name($exception): string
    {
        $collection = explode('\\', get_class($exception));
        return end($collection);
    }

    /**
     * Check if class exists in the namespace
     *
     * @param $className
     * @return bool
     */
    private function exists($className): bool
    {
        return class_exists(self::NAMESPACE . $className) ? true : false;
    }
}