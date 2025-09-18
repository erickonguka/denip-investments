<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Project;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        $staticPages = [
            ['url' => route('landing.index'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => route('about'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => route('services'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['url' => route('landing.projects'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['url' => route('careers'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['url' => route('landing.blog.index'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => route('contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => route('privacy-policy'), 'priority' => '0.3', 'changefreq' => 'yearly'],
            ['url' => route('terms-of-service'), 'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        foreach ($staticPages as $page) {
            $sitemap .= $this->generateUrlEntry($page['url'], now(), $page['changefreq'], $page['priority']);
        }

        // Blog posts
        $blogs = Blog::published()->get();
        foreach ($blogs as $blog) {
            $sitemap .= $this->generateUrlEntry(
                route('landing.blog.show', $blog->slug),
                $blog->updated_at,
                'monthly',
                '0.6'
            );
        }

        // Projects
        $projects = Project::where('status', 'completed')->get();
        foreach ($projects as $project) {
            $sitemap .= $this->generateUrlEntry(
                route('landing.project.show', $project->slug),
                $project->updated_at,
                'monthly',
                '0.7'
            );
        }

        // Careers
        $careers = Career::where('status', 'active')->get();
        foreach ($careers as $career) {
            $sitemap .= $this->generateUrlEntry(
                route('career.show', $career->slug),
                $career->updated_at,
                'weekly',
                '0.5'
            );
        }

        $sitemap .= '</urlset>';

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    private function generateUrlEntry($url, $lastmod, $changefreq, $priority)
    {
        $entry = "  <url>\n";
        $entry .= "    <loc>" . htmlspecialchars($url) . "</loc>\n";
        $entry .= "    <lastmod>" . $lastmod->format('Y-m-d\TH:i:s\Z') . "</lastmod>\n";
        $entry .= "    <changefreq>" . $changefreq . "</changefreq>\n";
        $entry .= "    <priority>" . $priority . "</priority>\n";
        $entry .= "  </url>\n";
        
        return $entry;
    }
}