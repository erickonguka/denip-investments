<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $careers = [
            [
                'title' => 'Site Engineer',
                'location' => 'Nairobi',
                'type' => 'full-time',
                'description' => 'We are seeking an experienced Site Engineer to oversee construction projects and ensure quality delivery. The ideal candidate will have strong technical skills and project management experience.',
                'requirements' => "• Bachelor's degree in Civil Engineering or related field\n• 3+ years of construction site experience\n• Knowledge of construction materials and methods\n• Strong problem-solving skills\n• Excellent communication abilities",
                'benefits' => "• Competitive salary\n• Health insurance\n• Professional development opportunities\n• Performance bonuses\n• Career advancement",
                'salary_min' => 80000,
                'salary_max' => 120000
            ],
            [
                'title' => 'Project Manager',
                'location' => 'Nairobi',
                'type' => 'full-time',
                'description' => 'Looking for an experienced Project Manager to lead construction projects from inception to completion. Must have proven track record in managing large-scale construction projects.',
                'requirements' => "• Bachelor's degree in Construction Management or Engineering\n• 5+ years of project management experience\n• PMP certification preferred\n• Strong leadership and team management skills\n• Proficiency in project management software",
                'benefits' => "• Competitive salary package\n• Health and life insurance\n• Company vehicle\n• Professional development\n• Performance incentives",
                'salary_min' => 150000,
                'salary_max' => 200000
            ],
            [
                'title' => 'Quantity Surveyor',
                'location' => 'Nairobi',
                'type' => 'full-time',
                'description' => 'We need a qualified Quantity Surveyor to manage project costs, prepare estimates, and ensure financial efficiency in our construction projects.',
                'requirements' => "• Bachelor's degree in Quantity Surveying\n• 2+ years of experience in construction costing\n• Knowledge of construction contracts\n• Proficiency in QS software\n• Attention to detail",
                'benefits' => "• Competitive remuneration\n• Medical cover\n• Training opportunities\n• Career growth\n• Annual leave",
                'salary_min' => 70000,
                'salary_max' => 100000
            ]
        ];

        foreach ($careers as $career) {
            Career::create($career);
        }
    }
}