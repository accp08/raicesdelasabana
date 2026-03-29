<?php

namespace App\Policies;

use App\Models\PropertyLead;
use App\Models\User;

class PropertyLeadPolicy
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

    public function view(User $user, PropertyLead $lead): bool
    {
        return $user->isEditor();
    }

    public function update(User $user, PropertyLead $lead): bool
    {
        return $user->isEditor();
    }

    public function delete(User $user, PropertyLead $lead): bool
    {
        return $user->isEditor();
    }
}
