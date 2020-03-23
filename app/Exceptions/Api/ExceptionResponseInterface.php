<?php


namespace App\Exceptions\Api;


interface ExceptionResponseInterface
{
    /**
     * @param $exception
     * @return mixed
     */
    public function response($exception);
}