<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenRepository implements TokenInterface
{
    /**
     * @param string $auth_type
     * @param array $data
     *
     * @return array
     */
    public function getToken($auth_type = 'login', $data)
    {
        $token = ($auth_type == 'login') ? Auth::attempt($data) : JWTAuth::fromUser($data);
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
