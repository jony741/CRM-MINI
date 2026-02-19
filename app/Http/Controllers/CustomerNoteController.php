<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerNote\StoreCustomerNoteRequest;
use App\Http\Requests\CustomerNote\UpdateCustomerNoteRequest;
use App\Models\Customer;
use App\Models\CustomerNote;
use App\Services\CustomerNoteService;
use Illuminate\Http\RedirectResponse;

class CustomerNoteController extends Controller
{
    public function __construct(
        protected CustomerNoteService $noteService
    ) {}

    public function store(StoreCustomerNoteRequest $request, Customer $customer): RedirectResponse
    {
        $this->noteService->create($customer, $request->validated(), $request->user());

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Note added successfully.');
    }

    public function update(UpdateCustomerNoteRequest $request, Customer $customer, CustomerNote $note): RedirectResponse
    {
        $this->noteService->update($note, $request->validated());

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Note updated successfully.');
    }

    public function destroy(Customer $customer, CustomerNote $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $this->noteService->delete($note);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Note deleted successfully.');
    }
}
