<?php

namespace App;


class Role extends Entity {

    public function validate($fields = false) {
        $rules['name'] = ['required', $this->unique()];
        $rules['rights'] = ['required'];
        $rules['description'] = ['required'];
        return $this->doValidation($rules, $fields);
    }

    public function validateDelete($fields = false) {
        $rules = [];
        $errors = $this->doValidation($rules, $fields);
        if ($this->users()->count()) {
            $errors->add('users', 'Role has users');
        }
        return $errors;
    }

    public function users() {
        return $this->hasManyThrough(User::class, "user_roles");
    }
}
