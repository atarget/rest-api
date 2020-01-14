<?php


namespace App\Api\Service\Implementation;


use App\Api\Service\Contract\FindQuery;
use App\Api\Service\Contract\IFindQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class DefaultFindQueryBuilder implements IFindQueryBuilder {

    /**
     * @var FindQuery $findQuery
     */
    private $findQuery;

    public function newBuilder($queryOrReq) {
        $builder = new DefaultFindQueryBuilder();
        if ($queryOrReq instanceof Request) {
            $queryOrReq = FindQuery::FromRequest($queryOrReq);
        }
        $builder->findQuery = $queryOrReq;
        return $builder;
    }

    /**
     * @param Builder $builder
     * @return integer
     */
    public function count($builder) {
        $builder = $builder->newQuery();
        $builder = $this->make($builder);
        return $builder->count();
    }

    /**
     * @param Builder $builder
     * @return integer
     */
    public function total($builder) {
        $builder = $builder->newQuery();
        return $builder->count();
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function limit($builder) {
        if ($this->findQuery->skip) $builder->skip($this->findQuery->skip);
        if ($this->findQuery->take) {
            $builder->take($this->findQuery->take);
        } else {
            $builder->take(200);
        }
        return $builder;
    }

    /**
     * @param Builder $builder
     */
    protected function processQueryArray($builder) {
        $model = $builder->getModel();
        foreach ($this->findQuery->query as $q) {
            $builder->where(function ($qb) use ($q, $model) {
                $model->searchable($qb, $q);
            });
        }
    }

    /**
     * @param Builder $builder
     */
    protected function fillFilter($builder, $fld, $op, $val) {
        switch ($op) {
            case ">":
            case "<":
            case "=":
            case "!=":
            case ">=":
            case "<=":
                $builder->where($fld, $op, $val);
                break;
            case "like":
            case "ilike":
                $builder->where($fld, $op, "%$val%");
                break;
        }
    }

    /**
     * @param Builder $builder
     */
    protected function processFilters($builder) {
        foreach ($this->findQuery->filters as $fld => $fltr) {
            foreach ($fltr as $op => $val) {
                $this->fillFilter($builder, $fld, $op, $val);
            }
        }
    }


    /**
     * @param Builder $builder
     * @return Builder
     */
    public function make($builder) {
        $this->processQueryArray($builder);
        $this->processFilters($builder);
        $builder->whereNotIn("id", $this->findQuery->ignore);
        $builder->with($this->findQuery->with);
        foreach ($this->findQuery->order as $fld => $dir) $builder->orderBy($fld, $dir);
        return $builder;
    }

}
