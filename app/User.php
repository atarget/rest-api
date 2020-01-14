<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Validation\Rule;
use Laravel\Lumen\Auth\Authorizable;

class User extends Entity implements AuthenticatableContract, AuthorizableContract {
    use Authenticatable, Authorizable;
    protected $table = "user";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function validate($fields = false) {
        $rules['username'] = ['required', $this->unique()];
        $rules['email'] = ['required', $this->unique(), 'email'];
        $rules['name'] = ['required'];
        if (!$this->id) $rules['password'] = ['required', 'min:8'];
        $errors = $this->doValidation($rules, $fields);
        if (filter_var($this->username, FILTER_VALIDATE_EMAIL)) {
            $errors->add("username", "Username is not email address");
        }
        return $errors;
    }

    public function validateDelete($fields = false) {
        $rules = [];
        return $this->doValidation($rules, $fields);
    }

    public function roles() {
        return $this->hasManyThrough(Role::class, "user_roles");
    }
}
