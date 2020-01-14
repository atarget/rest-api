<?php


namespace App\Api\Exceptions\Contract;


interface IError {
    /**
     * IError constructor.
     * @param mixed $data
     * @param string|null $msg
     */
    public function __construct($data, $msg = null);

    /**
     * @param string|null $msg
     * @return string
     */
    public function message($msg = null);

    /**
     * @param string|null $data
     * @return mixed
     */
    public function data($data = null);

    /**
     * @return string
     */
    public function toJson();
}
