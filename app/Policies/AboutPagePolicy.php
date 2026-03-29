<?php

namespace App\Policies;

use App\Models\AboutPage;
use App\Models\User;

class AboutPagePolicy
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

    public function view(User $user, AboutPage $aboutPage): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, AboutPage $aboutPage): bool
    {
        return $user->isEditor();
    }
}
