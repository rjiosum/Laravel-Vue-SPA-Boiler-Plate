<?php


namespace App\Iosum\Repositories\Interfaces\User;


interface UserRepositoryInterface
{
    /**
     * @param int $userId
     * @return mixed
     */
    public function getUserById(int $userId);

    /**
     * @param array $params
     * @return mixed
     */
    public function updateProfile(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function updatePassword(array $params);

}