<?php


namespace App\Api\Service\Implementation;


use App\Api\Service\Contract\ITranslationService;
use App\Translation;


class TranslationService extends AbstractService implements ITranslationService {
    public function save($entity, $query = null) {
        return parent::save($entity, Translation::query());
    }

    public function get($entity, $query = null) {
        return parent::get($entity, Translation::query());
    }

    public function find($findQueryBuilder, $query = null) {
        return parent::find($findQueryBuilder, Translation::query());
    }

    public function delete($entity, $query = null) {
        return parent::delete($entity, Translation::query());
    }

    public function makeEntity($data) {
        $translation = $this->makeEntityHelper($data, new Translation());
        return $translation;
    }
}
