<?php


namespace App\Repositories;

use App\Libraries\CommonFunction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthRepository
{
    public function register(array $data)
    {
        DB::beginTransaction();

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = app('hash')->make($data['password']);
        $user->save();

        DB::commit();

        return $user;
    }

    public function login(array $data)
    {
        try {
            $user = User::where('email', $data['email'])->first();

            // wrong email
            if (!isset($user)) {
                return [
                    'verify' => false,
                    'error' => response()->json(CommonFunction::dataResponse('Invalid email or password!', 'LOGIN', HTTPResponse::HTTP_UNAUTHORIZED))
                ];
            }

            // wrong password
            if (!Hash::check($data['password'], $user->password)) {
                return [
                    'verify' => false,
                    'error' => response()->json(CommonFunction::dataResponse('Invalid email or password!', 'LOGIN', HTTPResponse::HTTP_UNAUTHORIZED))
                ];
            }

            return ['verify' => true, 'data' => $user];

        } catch (\Exception $e) {
            return [
                'verify' => true,
                'error' => response()->json(CommonFunction::dataResponse($e->getMessage(), 'LOGIN', HTTPResponse::HTTP_UNAUTHORIZED))
            ];
        }
    }

    public function logout()
    {
        auth()->logout();
    }
}
