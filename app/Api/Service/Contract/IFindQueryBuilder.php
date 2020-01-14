<?php


namespace App\Api\Service\Contract;


use App\Api\Service\FindQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface IFindQueryBuilder {

    /**
     * @param FindQuery|Request $queryOrReq
     * @return mixed
     */
    public function newBuilder($queryOrReq);

    /**
     * @param Builder $builder
     * @return integer
     */
    public function count($builder);

    /**
     * @param Builder $builder
     * @return integer
     */
    public function total($builder);

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function make($builder);

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function limit($builder);

}
