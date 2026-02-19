<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('customers.view');
    }

    public function view(User $user, Customer $customer): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
            return $user->hasPermissionTo('customers.view');
        }

        return $user->hasPermissionTo('customers.view') &&
            ($customer->created_by === $user->id || $customer->assigned_to === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('customers.create');
    }

    public function update(User $user, Customer $customer): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
            return $user->hasPermissionTo('customers.update');
        }

        return $user->hasPermissionTo('customers.update') &&
            ($customer->created_by === $user->id || $customer->assigned_to === $user->id);
    }

    public function delete(User $user, Customer $customer): bool
    {
        if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
            return $user->hasPermissionTo('customers.delete');
        }

        return $user->hasPermissionTo('customers.delete') &&
            $customer->created_by === $user->id;
    }

    public function restore(User $user, Customer $customer): bool
    {
        return $this->delete($user, $customer);
    }

    public function forceDelete(User $user, Customer $customer): bool
    {
        return $user->hasRole('Admin');
    }

    public function assign(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('customers.assign');
    }
}
