<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {

        return $this->userRepository->create($data);
    }

    public function login(string $email, string $password)
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function updateProfile(int $userId, array $data)
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return null; // Atau throw exception
        }

        return $this->userRepository->update($user, $data);
    }

    public function getUserProfile(int $userId)
    {
        return $this->userRepository->findById($userId);
    }

    public function searchUserByName(string $name)
    {
        return $this->userRepository->searchByName($name);
    }
}
