<?php

namespace App\Http\Controllers;

use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    protected $auth_repo;
    protected $token_repo;

    public function __construct()
    {
        $this->auth_repo = App::make('App\Repositories\AuthInterface');
        $this->token_repo = App::make('App\Repositories\TokenInterface');
    }

    public function register(Request $request)
    {
        try {
            // input validation
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ];

            $validation = CommonFunction::inputValidationCheck($request->all(), $rules);
            if (!$validation['validation']) {
                return $validation['error'];
            }

            // Create new user
            $data['user'] = $this->auth_repo->register($request->all());

            if (!$data['user']) {
                return response()->json(
                    CommonFunction::dataResponse('Failed to create new user!Try again.', 'SIGN-UP', HTTPResponse::HTTP_UNAUTHORIZED)
                );
            }

            // token generate
            $data['token'] = $this->token_repo->getToken('register', $data['user']);
            if (!$data['token']['access_token']) {
                return response()->json(
                    CommonFunction::dataResponse('Failed to generate token!Try again.', 'SIGN-UP', HTTPResponse::HTTP_UNAUTHORIZED)
                );
            }

            // success response
            return response()->json(
                CommonFunction::dataResponse('Successfully sign-up user.', 'SIGN-UP', HTTPResponse::HTTP_OK, $data)
            );

        } catch (\Exception $e) {
            // exception generate & response
            return response()->json(
                CommonFunction::dataResponse($e->getMessage(), 'SIGN-UP', HTTPResponse::HTTP_BAD_REQUEST)
            );
        }
    }

    public function login(Request $request)
    {
        try {
            // input validation
            $rules = [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ];
            $validation = CommonFunction::inputValidationCheck($request->all(), $rules);

            if (!$validation['validation']) {
                return $validation['error'];
            }

            // Common check user
            $info = $this->auth_repo->login($request->all());

            if (!$info['verify']) {
                return $info['error'];
            }

            $data['user'] = $info['data'];

            // token generate
            $credentials = request(['email', 'password']);
            $data['token'] = $this->token_repo->getToken('login', $credentials);

            if (!$data['token']['access_token']) {
                return response()->json(
                    CommonFunction::dataResponse('Failed to generate token!Try again.', 'LOGIN', HTTPResponse::HTTP_UNAUTHORIZED)
                );
            }

            // success response
            return response()->json(
                CommonFunction::dataResponse('Successfully login.', 'LOGIN', HTTPResponse::HTTP_OK, $data)
            );

        } catch (\Exception $e) {
            // exception generate & response
            return response()->json(
                CommonFunction::dataResponse($e->getMessage(), 'LOGIN', HTTPResponse::HTTP_BAD_REQUEST)
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->logout();

            // success response
            return response()->json(
                CommonFunction::dataResponse('Successfully logged out.', 'LOGOUT', HTTPResponse::HTTP_OK)
            );

        } catch (\Exception $e) {
            // exception generate & response
            return response()->json(
                CommonFunction::dataResponse($e->getMessage(), 'LOGOUT', HTTPResponse::HTTP_BAD_REQUEST)
            );
        }
    }
}
