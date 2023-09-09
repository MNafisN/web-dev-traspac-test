<?php

namespace App\Http\Repositories;

use App\Models\User;

class UserRepository
{
    protected $users;

    public function __construct(User $user)
    {
        $this->users = $user;
    }

    /**
     * Untuk mengambil semua list user
     */
    public function getAll() : Object
    {
        $users = $this->users->get();
        return $users;
    }

    /**
     * Untuk mengambil data user berdasarkan email
     */
    public function getByEmail(string $email) : ?Object
    {
        $user = $this->users->where('email', $email)->first();
        return $user;
    }

    /**
     * Untuk menyimpan data user baru
     */
    public function store(array $data) : Object
    {
        $userBaru = new $this->users;

        $userBaru->email = $data['email'];
        $userBaru->email_verified_at = $data['verified_time'];
        $userBaru->password = bcrypt($data['password']);

        $userBaru->save();
        return $userBaru->fresh();
    }

    /**
     * Untuk menghapus data user berdasarkan email
     */
    public function delete(string $email) : void
    {
        $this->users->where('email', $email)->delete();
    }
}