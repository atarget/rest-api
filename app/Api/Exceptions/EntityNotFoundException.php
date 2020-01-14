<?php
namespace App\Api\Exceptions;

use App\Api\Exceptions\Contract\IError;
use Illuminate\Support\MessageBag;

class EntityNotFoundException extends \Exception implements IError {

    protected $message = null;
    /** @var MessageBag */
    protected $data = null;

    public function message($msg = null) {
        if ($msg) $this->message = $msg;
    }

    public function data($data = null) {
        if ($data) {
            $this->data = $data;
        } else if ($data instanceof MessageBag) {
            $this->data = $data;
        } else {
            $this->data = new MessageBag($data);
        }
    }

    public function toArray() {
        return [
            "message" => $this->message,
            "data" => $this->data
        ];
    }

    public function toJson() {
        return json_encode($this->toArray());
    }

    public function __toString() {
        return $this->toJson();
    }


}
