<?php

namespace App\Iosum\Repositories\Eloquent\User;


use App\Iosum\Repositories\Interfaces\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserRepository constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param int $userId
     * @return User|User[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getUserById(int $userId)
    {
        try {
            return $this->user->findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e);
        }
    }

    /**
     * @param array $params - user_id,first_name,last_name
     * @return mixed
     */
    public function updateProfile(array $params)
    {
        try {
            DB::beginTransaction();
            $update = $this->user
                ->where('id', $params['user_id'])
                ->update([
                    'first_name' => $params['first_name'],
                    'last_name' => $params['last_name']
                ]);
            DB::commit();
            return $update;

        } catch (QueryException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param array $params - user_id, password
     *
     * @return mixed
     */
    public function updatePassword(array $params)
    {
        try {
            DB::beginTransaction();
            $update = $this->user
                ->where('id', $params['user_id'])
                ->update([
                    'password' => bcrypt($params['password'])
                ]);
            DB::commit();
            return $update;
        } catch (QueryException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @param array $params - user_id, avatar
     * @return bool
     */
    public function updateAvatar(array $params)
    {
        try {
            DB::beginTransaction();
            $update = $this->user
                ->where('id', $params['user_id'])
                ->update([
                    'avatar' => $params['avatar']
                ]);
            DB::commit();
            return $update;
        } catch (QueryException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}