<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CustomerNoteService
{
    public function listForCustomer(Customer $customer, User $user): Collection
    {
        return $customer->notes()
            ->with('user')
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(Customer $customer, array $data, User $user): CustomerNote
    {
        return $customer->notes()->create([
            'user_id' => $user->id,
            'title' => $data['title'] ?? null,
            'content' => $data['content'],
            'is_private' => $data['is_private'] ?? false,
        ]);
    }

    public function update(CustomerNote $note, array $data): CustomerNote
    {
        $note->update([
            'title' => $data['title'] ?? $note->title,
            'content' => $data['content'] ?? $note->content,
            'is_private' => $data['is_private'] ?? $note->is_private,
        ]);

        return $note->fresh();
    }

    public function delete(CustomerNote $note): bool
    {
        return $note->delete();
    }
}
