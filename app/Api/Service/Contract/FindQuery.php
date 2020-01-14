<?php


namespace App\Api\Service\Contract;


use Illuminate\Http\Request;

class FindQuery {
    /** @var string $query - query for find */
    public $query;
    /** @var mixed $filters - mixed object { fieldName: { op: value }|value[, ...] } ex: { "name": "lol", "age": { ">": 10 }} */
    public $filters;
    /** @var array $with - with array ex: ["roles", "comments"] */
    public $with;
    /** @var integer $skip - skip records */
    public $skip;
    /** @var integer $take - take records */
    public $take;
    /** @var mixed $order - object { fieldName: direction[, ...] } ex: { name: 'asc' }*/
    public $order;
    /** @var array $ignore - ignore objects or object ids ex: [1, { id: 2, name: "aa"...}, 3]*/
    public $ignore;

    /**
     * @param Request $req
     * @return FindQuery
     */
    public static function FromRequest(Request $req) {
        $r = new FindQuery();
        $r->query = self::fixQuery($req->get("query"));
        $r->filters = self::fixFilters($req->get("filters"));
        $r->with = self::fixWith($req->get("with"));
        $r->skip = self::fixInt($req->get("skip"));
        $r->take = self::fixInt($req->get("take"));
        $r->order = self::fixOrder($req->get("order"));
        $r->ignore = self::fixIgnore($req->get("ignore"));
        return $r;
    }

    private static function fixQuery($query = null) {
        if (!$query) return [];
        $parts = explode(" ", $query);
        return $parts;
    }

    private static function fixOrder($order = null) {
        if (!$order) return [];
        return $order;
    }

    private static function fixInt($skip = null) {
        if (!$skip) return 0;
        return $skip;
    }

    private static function fixFilters($filters = []) {
        if (!$filters) return [];
        $result = [];
        foreach ($filters as $field => $filter) {
            if (!isset($result[$field])) {
                $result[$field] = [];
            }
            if (is_string($filter)) {
                $result[$field]["="] = $filter;
            } else {
                foreach ($filter as $op => $val) {
                    if (in_array($op, ["=", ">", "<", ">=", "<="])) {
                        $result[$field][$op] = $val;
                    } else if (in_array($op, ["like", "ilike"])) {
                        $result[$field][$op] = "%$val%";
                    }
                }
            }
        }
        return $result;
    }

    private static function fixWith($with = []) {
        if (!$with) return [];
        return (array)$with;
    }

    private static function fixIgnore($ignore = []) {
        if (!$ignore) return [];
        $ignore = (array)$ignore;
        $result = [];
        foreach ($ignore as $item) {
            if (isset($item['id'])) {
                $result[] = $item['id'];
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }
}
