<?php

namespace App\Services;


class HelperService
{
    /**
     * Return the directory path of a given value ($id = 123 will return 000/000/123/) .
     * @param int $id
     * @return string
     */
    public function path(int $id) : string
    {
        $id = 1000000000 + $id;
        $path = substr($id,1);
        $path = str_split($path,3);
        $path = implode('/', $path).'/';
        return $path;
    }

    /**
     * Returns a random string upper or lower case of specified length
     * @param int $length
     * @param bool $upper
     * @return string
     */
    public function randomKey(int $length, bool $upper = false) : string
    {
        $string = SHA1(microtime()).SHA1(microtime()).SHA1(microtime());
        $string = substr($string,rand(0,(64-$length)),$length);
        return ($upper === TRUE) ? strtoupper($string) : $string;
    }

    /**
     * @param int $min
     * @param int $max
     * @param int $quantity
     * @return array
     */
    public function uniqueRandomNumbers(int $min, int $max, int $quantity) : array
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }
}