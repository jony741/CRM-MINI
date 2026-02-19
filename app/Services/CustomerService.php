<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CustomerService
{
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Customer::query()
            ->with(['creator', 'assignee'])
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null);

        if (isset($filters['assigned_to'])) {
            $query->assignedTo($filters['assigned_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function listForUser(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Customer::query()
            ->with(['creator', 'assignee'])
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null);

        if ($user->hasRole('Admin') || $user->hasRole('Manager')) {
            // Admin and Manager can see all customers
        } else {
            // Regular users can only see their own customers
            $query->where(function (Builder $q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        if (isset($filters['assigned_to'])) {
            $query->assignedTo($filters['assigned_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function create(array $data, User $user): Customer
    {
        $data['created_by'] = $user->id;

        return Customer::create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer->fresh();
    }

    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }

    public function restore(Customer $customer): bool
    {
        return $customer->restore();
    }

    public function assign(Customer $customer, ?int $userId): Customer
    {
        $customer->update(['assigned_to' => $userId]);

        return $customer->fresh();
    }

    public function getStats(?User $user = null): array
    {
        $query = Customer::query();

        if ($user && !$user->hasRole('Admin') && !$user->hasRole('Manager')) {
            $query->where(function (Builder $q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        return [
            'total' => (clone $query)->count(),
            'active' => (clone $query)->where('status', 'active')->count(),
            'inactive' => (clone $query)->where('status', 'inactive')->count(),
            'leads' => (clone $query)->where('status', 'lead')->count(),
            'prospects' => (clone $query)->where('status', 'prospect')->count(),
        ];
    }

    public function getAssignedToUser(User $user): int
    {
        return Customer::where('assigned_to', $user->id)->count();
    }

    public function getRecentCustomers(int $limit = 5, ?User $user = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Customer::query()
            ->with(['creator', 'assignee'])
            ->orderBy('created_at', 'desc');

        if ($user && !$user->hasRole('Admin') && !$user->hasRole('Manager')) {
            $query->where(function (Builder $q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }

        return $query->limit($limit)->get();
    }
}
