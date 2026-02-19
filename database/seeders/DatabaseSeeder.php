<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolePermissionSeeder::class);

        // Seed users with roles
        $this->call(UserSeeder::class);

        // Get users for creating sample data
        $admin = User::where('email', 'admin@example.com')->first();
        $manager = User::where('email', 'manager@example.com')->first();
        $user = User::where('email', 'user@example.com')->first();

        // Create sample customers
        $customers = [];

        // Customers created by admin
        $customers[] = Customer::factory()->count(5)->create([
            'created_by' => $admin->id,
            'assigned_to' => $manager->id,
        ]);

        // Customers created by manager
        $customers[] = Customer::factory()->count(5)->create([
            'created_by' => $manager->id,
            'assigned_to' => $user->id,
        ]);

        // Customers created by regular user
        $customers[] = Customer::factory()->count(3)->create([
            'created_by' => $user->id,
            'assigned_to' => $user->id,
        ]);

        // Some unassigned customers
        $customers[] = Customer::factory()->count(2)->create([
            'created_by' => $admin->id,
            'assigned_to' => null,
        ]);

        // Add notes to some customers
        $allCustomers = Customer::all();
        foreach ($allCustomers->take(10) as $customer) {
            // Add 1-3 notes per customer
            CustomerNote::factory()
                ->count(rand(1, 3))
                ->create([
                    'customer_id' => $customer->id,
                    'user_id' => collect([$admin->id, $manager->id, $user->id])->random(),
                ]);
        }
    }
}
