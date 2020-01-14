<?php


namespace App\Api\Service\Contract;


use App\User;

interface IUserService extends IService {
    /**
     * @param string $token
     * @return null
     */
    public function setJWT($token);

    /**
     * @param string $username Username
     * @param string $password Password
     * @return string JWT
     */
    public function login($username, $password, $remember = false);

    /**
     * @return bool
     */
    public function logout();

    /**
     * @return User
     */
    public function currentUser();

    /**
     * @param string $right
     * @param int|null $id
     * @return boolean
     */
    public function can($right, $id = null);

    /**
     * @return string[]
     */
    public function rights();
}
