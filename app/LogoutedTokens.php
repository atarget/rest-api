<?php


namespace App;


use Illuminate\Support\MessageBag;

class LogoutedTokens extends Entity {
    public function validate($fields = false) {
        $rules['token'] = ['required'];
        return $this->doValidation($rules, $fields);
    }

    public function validateDelete($fields = false) {
        return $this->doValidation([], $fields);
    }

}
