<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected CustomerService $customerService
    ) {}

    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $stats = $this->customerService->getStats($user);
        $assignedToMe = $this->customerService->getAssignedToUser($user);
        $recentCustomers = $this->customerService->getRecentCustomers(5, $user);

        return view('dashboard', compact('stats', 'assignedToMe', 'recentCustomers'));
    }
}
