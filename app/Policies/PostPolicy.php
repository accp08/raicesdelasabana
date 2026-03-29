<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isEditor();
    }

    public function view(User $user, Post $post): bool
    {
        return $user->isEditor();
    }

    public function create(User $user): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, Post $post): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->isEditor();
    }
}
