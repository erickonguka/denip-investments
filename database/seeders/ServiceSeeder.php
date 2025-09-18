<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'name' => 'Residential Construction',
                'description' => 'Complete residential construction services from foundation to finishing, creating dream homes for families across Kenya.',
                'icon' => 'fas fa-home',
                'features' => [
                    'Custom home design',
                    'Foundation and structural work',
                    'Electrical and plumbing installation',
                    'Interior and exterior finishing',
                    'Landscaping services'
                ],
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Commercial Construction',
                'description' => 'Professional commercial construction services for offices, retail spaces, and industrial facilities.',
                'icon' => 'fas fa-building',
                'features' => [
                    'Office building construction',
                    'Retail space development',
                    'Warehouse construction',
                    'Industrial facilities',
                    'Commercial renovations'
                ],
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Infrastructure Development',
                'description' => 'Large-scale infrastructure projects including roads, bridges, and public facilities.',
                'icon' => 'fas fa-road',
                'features' => [
                    'Road construction',
                    'Bridge building',
                    'Water systems',
                    'Public facilities',
                    'Urban planning'
                ],
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Renovation & Remodeling',
                'description' => 'Transform existing spaces with our comprehensive renovation and remodeling services.',
                'icon' => 'fas fa-tools',
                'features' => [
                    'Kitchen remodeling',
                    'Bathroom renovation',
                    'Office space upgrades',
                    'Structural modifications',
                    'Energy efficiency improvements'
                ],
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}