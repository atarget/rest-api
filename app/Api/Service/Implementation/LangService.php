<?php


namespace App\Api\Service\Implementation;


use App\Api\Service\Contract\ITranslationService;
use App\Lang;


class LangService extends AbstractService implements ITranslationService {
    public function save($entity, $query = null) {
        return parent::save($entity, Lang::query());
    }

    public function get($entity, $query = null) {
        return parent::get($entity, Lang::query());
    }

    public function find($findQueryBuilder, $query = null) {
        return parent::find($findQueryBuilder, Lang::query());
    }

    public function delete($entity, $query = null) {
        return parent::delete($entity, Lang::query());
    }

    public function makeEntity($data) {
        $translation = $this->makeEntityHelper($data, new Lang());
        return $translation;
    }
}
