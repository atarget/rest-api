<?php

namespace App;


class Lang extends Entity {
    public function validate($fields = false) {
        $rules = [];
        $rules["name"] = ["required"];
        $rules["description"] = ["required"];
        $rules["code"] = ["required"];
        return $this->doValidation($rules, $fields);
    }

    public function validateDelete($fields = false) {
        return $this->doValidation();
    }

    public function delete() {
        Translation::query()->where("lang", $this->code)->delete();
        return parent::delete();
    }

    public function save(array $options = []) {
        if ($this->id) {
            $old = Lang::query()->find($this->id);
            Translation::query()->where("lang", $old->code)->update(["lang" => $this->code]);
        }
        return parent::save($options);
    }


}
