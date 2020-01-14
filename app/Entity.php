<?php


namespace App;


use Egulias\EmailValidator\EmailValidator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rules\Unique;

abstract class Entity extends Model {

    public function save(array $options = []) {
        if (!$this->sort) {
            $sort = \DB::table($this->getTable())->orderBy("sort", "desc")->first();
            if (!$sort) {
                $this->sort = 1;
            } else {
                $this->sort = $sort->sort + 0.001;
            }
        }
        return parent::save($options);
    }

    /**
     * @param array|false $fields
     * @return MessageBag
     */
    public abstract function validate($fields = false);

    /**
     * @param array|false $fields
     * @return MessageBag
     */
    public abstract function validateDelete($fields = false);

    protected function clearRules($rules = [], $fields = false) {
        if (!$fields) return $rules;
        $result = [];
        foreach ($rules as $field => $fieldRules) {
            if (isset($rules[$field])) {
                $result[$field] = $fieldRules;
            }
        }
        return $result;
    }



    protected function unique() {
        $unique = new Unique($this->getTable());
        $unique->ignore($this->id);
        return $unique;
    }

    protected function doValidation($rules = [], $fields = false) {
        $rules = $this->clearRules($rules, $fields);
        $data = $this->toArray();
        foreach ($this->hidden as $f) {
            $data[$f] = $this[$f];
        }
        $validator = \Validator::make($data, $rules);
        return $validator->errors();
    }

    /**
     * @param Builder $builder
     */
    public function searchable($builder, $q) {
        foreach ($this->fillable as $f) {
            $builder->orWhere($f, "like", "%$q%");
        }
    }
}
