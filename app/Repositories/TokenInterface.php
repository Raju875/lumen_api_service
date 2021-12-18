<?php

namespace App\Repositories;

interface TokenInterface
{
    public function getToken(string $auth_type, array $data);
}
