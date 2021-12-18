<?php

namespace App\Repositories;

interface AuthInterface
{
    public function register(array $data);

    public function login(array $data);

    public function logout();
}
