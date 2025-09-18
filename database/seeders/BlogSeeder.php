<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\User;

class BlogSeeder extends Seeder
{
    public function run()
    {
        // Create blog categories
        $categories = [
            [
                'name' => 'Construction Tips',
                'slug' => 'construction-tips',
                'description' => 'Expert tips and best practices for construction projects',
                'color' => '#3b82f6'
            ],
            [
                'name' => 'Project Updates',
                'slug' => 'project-updates',
                'description' => 'Latest updates from our ongoing construction projects',
                'color' => '#10b981'
            ],
            [
                'name' => 'Industry News',
                'slug' => 'industry-news',
                'description' => 'Latest news and trends in the construction industry',
                'color' => '#f59e0b'
            ]
        ];

        foreach ($categories as $categoryData) {
            BlogCategory::create($categoryData);
        }

        // Get first admin user
        $admin = User::where('role', 'admin')->first() ?? User::first();

        if (!$admin) {
            return;
        }

        // Create sample blog posts
        $blogs = [
            [
                'title' => '10 Essential Tips for Successful Construction Project Management',
                'slug' => '10-essential-tips-successful-construction-project-management',
                'excerpt' => 'Learn the key strategies that separate successful construction projects from failed ones. From planning to execution, these tips will help you deliver projects on time and within budget.',
                'content' => '<h2>Introduction</h2><p>Construction project management is a complex field that requires careful planning, coordination, and execution. Whether you\'re managing a small residential project or a large commercial development, these essential tips will help ensure your project\'s success.</p><h2>1. Detailed Planning is Everything</h2><p>Before breaking ground, invest significant time in planning every aspect of your project. This includes creating detailed schedules, identifying potential risks, and establishing clear communication channels with all stakeholders.</p><h2>2. Budget Management</h2><p>Keep a close eye on your budget throughout the project lifecycle. Regular budget reviews and cost tracking will help you identify potential overruns early and take corrective action.</p><h2>3. Quality Control</h2><p>Implement robust quality control measures at every stage of construction. Regular inspections and adherence to building codes and standards are non-negotiable.</p><h2>Conclusion</h2><p>Successful construction project management requires attention to detail, strong communication skills, and the ability to adapt to changing circumstances. By following these tips, you\'ll be well on your way to delivering successful projects.</p>',
                'category_id' => 1,
                'author_id' => $admin->id,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'meta_keywords' => ['construction', 'project management', 'building', 'tips', 'success'],
                'meta_description' => 'Discover 10 essential tips for successful construction project management. Learn how to plan, execute, and deliver construction projects on time and within budget.',
                'views' => 245
            ],
            [
                'title' => 'Sustainable Building Materials: The Future of Construction',
                'slug' => 'sustainable-building-materials-future-construction',
                'excerpt' => 'Explore the latest sustainable building materials that are revolutionizing the construction industry. From recycled steel to bamboo, discover eco-friendly alternatives that don\'t compromise on quality.',
                'content' => '<h2>The Green Revolution in Construction</h2><p>The construction industry is undergoing a significant transformation as sustainability becomes a priority for builders, developers, and clients alike. Sustainable building materials are no longer just an option – they\'re becoming the standard.</p><h2>Top Sustainable Materials</h2><h3>Recycled Steel</h3><p>Steel is one of the most recycled materials in the world, and using recycled steel in construction can significantly reduce environmental impact while maintaining structural integrity.</p><h3>Bamboo</h3><p>Bamboo is a rapidly renewable resource that offers excellent strength-to-weight ratio. It\'s increasingly being used for flooring, structural elements, and decorative features.</p><h3>Reclaimed Wood</h3><p>Using reclaimed wood not only reduces waste but also adds character and history to new constructions. It\'s perfect for both structural and aesthetic applications.</p><h2>Benefits of Sustainable Materials</h2><p>Beyond environmental benefits, sustainable materials often offer cost savings over the long term, improved indoor air quality, and enhanced building performance.</p>',
                'category_id' => 3,
                'author_id' => $admin->id,
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'meta_keywords' => ['sustainable', 'building materials', 'eco-friendly', 'green construction', 'environment'],
                'meta_description' => 'Learn about sustainable building materials that are shaping the future of construction. Discover eco-friendly alternatives that offer both environmental and economic benefits.',
                'views' => 189
            ],
            [
                'title' => 'Denip Investments Completes Major Commercial Complex in Nairobi',
                'slug' => 'denip-investments-completes-major-commercial-complex-nairobi',
                'excerpt' => 'We\'re proud to announce the successful completion of the Westlands Business Park, a state-of-the-art commercial complex that will house over 50 businesses and create hundreds of jobs.',
                'content' => '<h2>Project Overview</h2><p>After 18 months of intensive construction, we\'re thrilled to announce the completion of the Westlands Business Park, one of Nairobi\'s most ambitious commercial developments.</p><h2>Key Features</h2><ul><li>50,000 square meters of premium office space</li><li>Modern amenities including fitness center and conference facilities</li><li>Sustainable design with LEED Gold certification</li><li>Underground parking for 500 vehicles</li><li>24/7 security and building management systems</li></ul><h2>Construction Highlights</h2><p>The project presented several unique challenges, including working in a densely populated urban area and incorporating advanced sustainable technologies. Our team successfully navigated these challenges while maintaining the highest safety and quality standards.</p><h2>Community Impact</h2><p>The Westlands Business Park is expected to create over 2,000 direct and indirect jobs, contributing significantly to the local economy. The building also incorporates retail spaces that will serve the surrounding community.</p><h2>Looking Forward</h2><p>This project represents our commitment to delivering world-class commercial spaces that meet the evolving needs of modern businesses while contributing positively to Kenya\'s economic growth.</p>',
                'category_id' => 2,
                'author_id' => $admin->id,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'meta_keywords' => ['Denip Investments', 'Nairobi', 'commercial complex', 'Westlands', 'construction project'],
                'meta_description' => 'Denip Investments successfully completes the Westlands Business Park, a major commercial complex in Nairobi featuring 50,000 square meters of premium office space.',
                'views' => 312
            ]
        ];

        foreach ($blogs as $blogData) {
            Blog::create($blogData);
        }
    }
}