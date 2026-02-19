<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;

class CustomerNotePolicy
{
    public function viewAny(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('notes.view');
    }

    public function view(User $user, CustomerNote $note): bool
    {
        if ($user->hasRole('Admin')) {
            return true;
        }

        if ($note->is_private && $note->user_id !== $user->id) {
            return false;
        }

        return $user->hasPermissionTo('notes.view');
    }

    public function create(User $user, Customer $customer): bool
    {
        if (!$user->hasPermissionTo('notes.create')) {
            return false;
        }

        if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
            return true;
        }

        return $customer->created_by === $user->id || $customer->assigned_to === $user->id;
    }

    public function update(User $user, CustomerNote $note): bool
    {
        if ($user->hasRole('Admin')) {
            return $user->hasPermissionTo('notes.update');
        }

        return $user->hasPermissionTo('notes.update') && $note->user_id === $user->id;
    }

    public function delete(User $user, CustomerNote $note): bool
    {
        if ($user->hasRole('Admin')) {
            return $user->hasPermissionTo('notes.delete');
        }

        return $user->hasPermissionTo('notes.delete') && $note->user_id === $user->id;
    }
}
