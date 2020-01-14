<?php

namespace App\Api\Service\Contract;

interface IMenuService extends IService {
    public function canActivate($path);
}
