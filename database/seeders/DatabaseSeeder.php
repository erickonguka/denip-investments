<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            SettingsSeeder::class,
        ]);

        // Create sample clients
        $clients = [
            [
                'name' => 'John Smith',
                'company' => 'Acme Corporation',
                'email' => 'john@acme.com',
                'phone' => '+1-555-0123',
                'type' => 'corporate',
                'status' => 'active'
            ],
            [
                'name' => 'Sarah Johnson',
                'company' => 'Tech Solutions Ltd',
                'email' => 'sarah@techsol.com',
                'phone' => '+1-555-0124',
                'type' => 'corporate',
                'status' => 'active'
            ],
            [
                'name' => 'Mike Wilson',
                'company' => null,
                'email' => 'mike@email.com',
                'phone' => '+1-555-0125',
                'type' => 'individual',
                'status' => 'active'
            ]
        ];

        foreach ($clients as $clientData) {
            $client = Client::firstOrCreate(
                ['email' => $clientData['email']],
                $clientData
            );

            // Create sample projects for each client
            Project::firstOrCreate(
                ['title' => 'Website Redesign', 'client_id' => $client->id],
                [
                    'description' => 'Complete website redesign and development',
                    'start_date' => now()->subDays(30),
                    'end_date' => now()->addDays(30),
                    'budget' => 15000.00,
                    'status' => 'active',
                    'progress' => 75
                ]
            );
        }
    }
}
