<?php

namespace App;


class Translation extends Entity {
    public function validate($fields = false) {
        $rules = [];
        $rules["text"] = ["required"];
        $rules["lang"] = ["required"];
        $rules["translation"] = ["translation"];
        $errors = $this->doValidation($rules, $fields);
        if ($this->translatable_type || $this->translatable_id || $this->translatable_field) {
            if (!$this->translatable_id) {
                $errors->add("translatable_id", "Translatable id is required if translatable is present");
            }
            if (!$this->translatable_field) {
                $errors->add("translatable_field", "Translatable field is required if translatable is present");
            }
            if (!$this->translatable_type) {
                $errors->add("translatable_type", "Translatable type is required if translatable is present");
            }
        }
        $lang = $rules["lang"];
        if (!Lang::query()->where("code", $lang)->count()) {
            $errors->add("lang", "Language ${lang} does not exists");
        }
        return $errors;
    }

    public function validateDelete($fields = false) {
        return $this->doValidation();
    }

}
