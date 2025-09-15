<?php declare(strict_types = 1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Group;
use App\Models\User;

final class GroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role == UserRole::ADMIN;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Group $group): bool
    {
        return $user->groups->contains($group);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == UserRole::ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::GROUP_ADMIN]) and $user->groups->contains($group);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::GROUP_ADMIN]) and $user->groups->contains($group);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Group $group): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::GROUP_ADMIN]) and $user->groups->contains($group);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Group $group): bool
    {
        return in_array($user->role, [UserRole::ADMIN, UserRole::GROUP_ADMIN]) and $user->groups->contains($group);
    }
}
