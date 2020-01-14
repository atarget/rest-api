<?php


namespace App\Api\Service\Implementation;

use App\Api\Service\Contract\IUserService;
use App\LogoutedTokens;
use App\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Support\Facades\Hash;
use \Firebase\JWT\JWT;

class UserService extends AbstractService implements IUserService {


    public function save($entity, $query = null) {
        return parent::save($entity, User::query());
    }

    public function get($entity, $query = null) {
        return parent::get($entity, User::query());
    }

    public function find($findQueryBuilder, $query = null) {
        return parent::find($findQueryBuilder, User::query());
    }

    public function delete($entity, $query = null) {
        return parent::delete($entity, User::query());
    }

    public function makeEntity($data) {
        $user = $this->makeEntityHelper($data, new User());
        if (!empty($data['password'])) {
            $user->password = $data['password'];
            if (Hash::needsRehash($user->password)) $user->password = Hash::make($user->password);
        }
        if (!$user->password) unset($user->password);
        return $user;
    }

    protected function getJWTKey() {
        return env("JWT_SECRET");
    }

    protected function getJWTExpiration() {
        return env("JWT_EXPIRATION");
    }

    private $token = "";
    public function setJWT($token) {
        $this->token = $token;
    }


    public function login($username, $password, $remember = false) {
        $user = User::query()->where('username', "=", $username)
            ->orWhere("email", "=", "username")
            ->first();
        if (!$user) return "";
        if (Hash::check($password, $user->password)) {
            $payload = [];
            if (!$remember) $payload["exp"] = time() + $this->getJWTExpiration();
            $payload["userId"] = $user->id;
            return JWT::encode($payload, $this->getJWTKey(), 'HS256');
        }
        return "";
    }

    public function logout() {
        $token = new LogoutedTokens();
        $token->token = $this->token;
        $token->save();
    }

    public function currentUser() {
        if (!$this->token) return null;
        try {
            $payload = JWT::decode($this->token, $this->getJWTKey(), ['HS256']);
            $userId = $payload->userId;
            if (LogoutedTokens::query()->where("token", $this->token)->count()) {
                return null;
            }
            $user = User::query()->where("id", $userId)->first();
            return $user;
        } catch (ExpiredException $e) {
            LogoutedTokens::query()->where("token", $this->token)->delete();
        } catch (SignatureInvalidException $e) {
        }
        return null;
    }

    public function rights() {
        $user = $this->currentUser();
        $rights = [];
        if ($user) {
            $roles = $user->roles();
            foreach ($roles as $role) {
                $roleRights = explode(" ", $role->rights);
                $rights = array_merge($rights, $roleRights);
            }
        }
        return $rights;
    }



    public function can($right, $id = null) {
        $rights = $this->rights();
        foreach ($rights as $userRight) {
            $userRight = str_replace(".", "\\.", $userRight);
            $userRight = str_replace("*", ".*", $userRight);
            if (preg_match("~$userRight~", $right)) return true;
        }
        return false;
    }

}

