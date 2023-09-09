<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\UserService;
use Illuminate\Http\JsonResponse;
use Exception;
use App\Exceptions\ArrayException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Register a new user
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) : JsonResponse
    {
        $data = $request->all();

        try {
            $user = $this->userService->store($data);
            $result = [
                'status' => 201,
                'message' => 'User registered successfully',
                'registered_user' => $user->only(['email'])
            ];
        } catch (ArrayException $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessagesArray()
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Log in and get a JWT token via given credentials
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) : JsonResponse
    {
        $credentials = $request->all();

        try {
            $token = $this->userService->login($credentials);
            if (!$token) { return response()->json(['status' => 401, 'error' => 'Wrong email, please try again'], 401); }

            $result = [
                'status' => 200,
                'logged_in_user' => [
                    'email' => auth()->user()['email']
                ],
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ];
        } catch (ArrayException $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessagesArray()
            ];
        } catch (Exception $err) {
            $result = [
                'status' => 422,
                'error' => $err->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Get current logged in user's data, for edit profile form
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data() : JsonResponse
    {
        $result = [
            'status' => 200,
            'user_data' => $this->userService->data()
        ];

        return response()->json($result, $result['status']);
    }

        /**
     * Get a new JWT token for the logged in user
     *
     * @return \Illuminate\Http\JsonResponse
     */

     public function refresh() : JsonResponse
     {
         $result = [
             'status' => 200,
             'access_token' => auth()->refresh(),
             'token_type' => 'bearer',
             'expires_in' => auth()->factory()->getTTL() * 60
         ];
 
         return response()->json($result, $result['status']);
     }
 
     /**
      * Log the user out (Invalidate the token)
      *
      * @return \Illuminate\Http\JsonResponse
      */
     public function logout() : JsonResponse
     {
         $message = $this->userService->logout().' Successfully logged out';
 
         $result = [
             'status' => 200,
             'message' => $message
         ];
 
         return response()->json($result, $result['status']);
     }
}
