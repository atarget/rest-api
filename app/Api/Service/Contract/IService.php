<?php

namespace App\Api\Service\Contract;

use App\Api\Exceptions\Contract\IError;
use App\Api\Exceptions\ValidationError;
use App\Api\Service\FindResponse;
use App\Entity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;

interface IService {

    /**
     * @param MessageBag $errors
     * @throws ValidationError
     */
    public function throwValidation($errors = null);

    /**
     * @param MessageBag $errors
     * @param Entity|array $entity
     * @return MessageBag
     */
    public function validateDelete($entity, $errors = null);

    /**
     * @param MessageBag $errors
     * @param Entity|array $entity
     * @return MessageBag
     */
    public function validate($entity, $errors = null);

    /**
     * @param array|Entity|array[]|entity[] $entity
     * @param Builder $query
     * @return Entity|Collection<Entity>
     * @throws IError
     */
    public function save($entity, $query = null);

    /**
     * @param integer|Entity $entity
     * @param Builder $query
     * @return Entity
     * @throws IError
     */
    public function get($entity, $query = null);

    /**
     * @param IFindQueryBuilder $findQueryBuilder
     * @param Builder $query
     * @return FindResponse
     * @throws IError
     */
    public function find($findQueryBuilder, $query = null);

    /**
     * @param integer|Entity|integer[]|Entity[] $entity
     * @param Builder $query
     * @return bool
     * @throws IError
     */
    public function delete($entity, $query = null);

    /**
     * @param array $data
     * @return Entity
     */
    public function makeEntity($data);
}
