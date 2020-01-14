<?php

namespace App;


class Menu extends Entity {

    public function validate($fields = false) {
        $rules = [];
        $rules['name'] = ['required'];
        $rules['description'] = ['required'];
        $rules['path'] = ['required', $this->unique()];
        $rules['right'] = ['required'];
        return $this->doValidation($rules, $fields);
    }

    public function validateDelete($fields = false) {
        $rules = [];
        $errors = $this->doValidation($rules, $fields);
        if ($this->children()->count()) {
            $errors->add('children', 'Menu has childrens');
        }
        return $errors;
    }

    public function parent() {
        return $this->belongsTo(Menu::class, "parent_id");
    }

    public function children() {
        return $this->hasMany(Menu::class, "parent_id");
    }
}
