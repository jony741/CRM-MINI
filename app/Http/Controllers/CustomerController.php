<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Services\CustomerService;
use App\Services\CustomerNoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerService $customerService,
        protected CustomerNoteService $noteService
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Customer::class);

        $filters = $request->only(['search', 'status', 'sort_by', 'sort_order', 'assigned_to']);
        $perPage = $request->input('per_page', 15);

        $customers = $this->customerService->listForUser($request->user(), $filters, $perPage);

        return view('customers.index', compact('customers', 'filters'));
    }

    public function create(): View
    {
        $this->authorize('create', Customer::class);

        $users = User::all();
        $statuses = ['active', 'inactive', 'lead', 'prospect'];

        return view('customers.create', compact('users', 'statuses'));
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = $this->customerService->create($request->validated(), $request->user());

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): View
    {
        $this->authorize('view', $customer);

        $customer->load(['creator', 'assignee']);
        $notes = $this->noteService->listForCustomer($customer, request()->user());

        return view('customers.show', compact('customer', 'notes'));
    }

    public function edit(Customer $customer): View
    {
        $this->authorize('update', $customer);

        $users = User::all();
        $statuses = ['active', 'inactive', 'lead', 'prospect'];

        return view('customers.edit', compact('customer', 'users', 'statuses'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->customerService->update($customer, $request->validated());

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->authorize('delete', $customer);

        $this->customerService->delete($customer);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function restore(Customer $customer): RedirectResponse
    {
        $this->authorize('restore', $customer);

        $this->customerService->restore($customer);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer restored successfully.');
    }

    public function assign(Request $request, Customer $customer): RedirectResponse
    {
        $this->authorize('assign', $customer);

        $request->validate([
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $this->customerService->assign($customer, $request->input('assigned_to'));

        return redirect()
            ->back()
            ->with('success', 'Customer assigned successfully.');
    }
}
