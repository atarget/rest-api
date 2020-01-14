<?php


namespace App\Api\Service\Contract;


class FindResponse {
    /** @var integer $total */
    public $total;
    /** @var integer $count */
    public $count;
    /** @var array $items */
    public $items;

    public function __toString() {
        return json_encode($this);
    }
}
