<?php
namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function findByUsername(string $username): ?User;

    public function revokeToken(User $user): void;
}
?>