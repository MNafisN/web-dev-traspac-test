<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use Carbon\Carbon;
use InvalidArgumentException;
use App\Exceptions\ArrayException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Untuk mengambil semua list user
     */
    public function getAll() : ?Object
    {
        $users = $this->userRepository->getAll();
        $user = $users->isEmpty() ? null : $users;
        return $user;
    }

    /**
     * Untuk menambahkan user
     */
    public function store(array $data) : Object
    {
        $validator = Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) { throw new ArrayException($validator->errors()->toArray()); }

        if ((filter_var($data['email'], FILTER_VALIDATE_EMAIL)) !== false) {
            $data['verified_time'] = (string)Carbon::now();
        } else { throw new InvalidArgumentException('invalid email'); }

        $result = $this->userRepository->store($data);
        return $result;
    }

    /**
     * Untuk melakukan login user dengan data yang diperlukan
     */
    public function login(array $credentials) : string|bool
    {
        $validator = Validator::make($credentials, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) { throw new ArrayException($validator->errors()->toArray()); }

        $token = auth()->attempt($credentials, true);

        return $token;
    }

    /**
     * Untuk melihat detail user yang telah dalam keadaan logged in
     */
    public function data() : array
    {
        $user = auth()->user();
        return $user->only(['email']);
    }

    /**
     * Untuk melakukan logout pada user
     */
    public function logout() : string
    {
        $username = auth()->user()['email'];
        auth()->logout();
        return $username;
    }
}