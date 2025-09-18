<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'ENGINEERING DESIGNS',
                'description' => 'Comprehensive engineering design services for various projects',
                'icon' => 'fas fa-drafting-compass'
            ],
            [
                'name' => 'GENERAL CONSTRUCTION WORKS',
                'description' => 'Complete construction services from foundation to finishing',
                'icon' => 'fas fa-hammer'
            ],
            [
                'name' => 'SURVEYING AND PLANNING',
                'description' => 'Professional surveying and project planning services',
                'icon' => 'fas fa-map'
            ],
            [
                'name' => 'ARCHITECTURAL DESIGNS AND MODELLING',
                'description' => 'Architectural design and 3D modeling services',
                'icon' => 'fas fa-building'
            ],
            [
                'name' => 'FLOORING AND WATER PROOFING SOLUTIONS',
                'description' => 'Specialized flooring and waterproofing solutions',
                'icon' => 'fas fa-layer-group'
            ],
            [
                'name' => 'GENERAL SUPPLY AND DELIVERY SERVICES',
                'description' => 'Supply and delivery of construction materials and equipment',
                'icon' => 'fas fa-truck'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}