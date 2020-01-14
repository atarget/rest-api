<?php


namespace App\Api\Service\Implementation;


use App\Api\Exceptions\Contract\IError;
use App\Api\Exceptions\EntityNotFoundException;
use App\Api\Exceptions\ValidationError;
use App\Api\Service\Contract\IService;
use App\Api\Service\Contract\FindResponse;
use App\Entity;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;

abstract class AbstractService implements IService {
    /**
     * @param MessageBag $errors
     */
    public function throwValidation($errors = null) {
        if (!$errors || !$errors->count()) return;
        $exception = new ValidationError();
        $exception->message("validation error");
        $exception->data($errors);
        throw $exception;
    }

    /**
     * @param Entity|array $entity
     * @param null $errors
     * @return MessageBag|void
     */
    public function validate($entity, $errors = null) {
        if (!($entity instanceof Entity)) {
            $entity = $this->makeEntity($entity);
        }
        if (!$errors) {
            $errors = new MessageBag();
        }
        $entityErrors = $entity->validate();
        return $errors->merge($entityErrors);
    }

    protected function makeEntityHelper($data, $entity) {
        if ($data instanceof Entity) return $data;
        $entity->fill($data);
        return $entity;
    }

    protected function extractId($mayBeIdAble) {
        if (isset($mayBeIdAble->id)) return $mayBeIdAble->id;
        if (isset($mayBeIdAble['id'])) return $mayBeIdAble['id'];
        return $mayBeIdAble;
    }

    /**
     * @param Entity|int $entity
     * @param Builder $query
     * @return Entity|int
     * @throws EntityNotFoundException
     */
    public function get($entity, $query = null) {
        $result = $query->find($this->extractId($entity));
        if (!$result) {
            $nfe = new EntityNotFoundException();
            $nfe->message("entity not found");
            $nfe->data(["entity" => $entity, "model" => get_class($query->getModel())]);
            throw $nfe;
        }
        return $result;
    }

    /**
     * @param $entity
     * @param Builder $query
     * @return mixed
     * @throws EntityNotFoundException
     */
    public function delete($entity, $query = null) {
        $entity = $this->get($entity);
        $errors = $this->validateDelete($entity);
        $this->throwValidation($errors);
        $entity = $query->where("id", $this->extractId($entity))->delete();
        return $entity;
    }

    /**
     * @param \App\Api\Service\Contract\IFindQueryBuilder $findQueryBuilder
     * @param Builder $query
     * @return FindResponse
     */
    public function find($findQueryBuilder, $query = null) {
        $result = new FindResponse();
        $result->total = $findQueryBuilder->total($query);
        $result->count = $findQueryBuilder->count($query);
        $findQueryBuilder->make($query);
        $result->items = $findQueryBuilder->limit($query)->get();
        return $result;
    }

    public function save($entity, $query = null) {
        $entity = $this->makeEntity($entity);
        $errors = $this->validate($entity);
        $this->throwValidation($errors);
        $entity->save();
    }


    /**
     * @param Entity|array $entity
     * @param null $errors
     * @return MessageBag|void
     */
    public function validateDelete($entity, $errors = null) {
        if (!($entity instanceof Entity)) {
            $entity = $this->makeEntity($entity);
        }
        if (!$errors) {
            $errors = new MessageBag();
        }
        $entityErrors = $entity->validate();
        return $errors->merge($entityErrors);
    }
}
