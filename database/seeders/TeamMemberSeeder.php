<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeamMember;

class TeamMemberSeeder extends Seeder
{
    public function run()
    {
        $teamMembers = [
            [
                'name' => 'John Kamau',
                'position' => 'Chief Executive Officer',
                'bio' => 'With over 15 years of experience in construction management, John leads our team with vision and expertise.',
                'email' => 'john.kamau@denipinvestments.com',
                'phone' => '+254 700 123 456',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Wanjiku',
                'position' => 'Project Manager',
                'bio' => 'Sarah ensures all projects are delivered on time and within budget, with exceptional attention to detail.',
                'email' => 'sarah.wanjiku@denipinvestments.com',
                'phone' => '+254 700 123 457',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Michael Ochieng',
                'position' => 'Lead Engineer',
                'bio' => 'Michael brings technical excellence to every project with his engineering expertise and innovative solutions.',
                'email' => 'michael.ochieng@denipinvestments.com',
                'phone' => '+254 700 123 458',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($teamMembers as $member) {
            TeamMember::create($member);
        }
    }
}