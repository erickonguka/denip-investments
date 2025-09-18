<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $projects = Project::where('is_public', true)
            ->with(['client', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
            
        $categories = Category::where('is_active', true)
            ->withCount(['projects' => function($query) {
                $query->where('is_public', true);
            }])
            ->get();
            
        return view('landing.index', compact('projects', 'categories'));
    }

    public function projects(Request $request)
    {
        $query = Project::where('is_public', true)
            ->with(['client', 'category']);
            
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        $projects = $query->orderBy('created_at', 'desc')->paginate(12);
        
        $categories = Category::where('is_active', true)
            ->withCount(['projects' => function($query) {
                $query->where('is_public', true);
            }])
            ->get();
            
        return view('landing.projects', compact('projects', 'categories'));
    }

    public function careers()
    {
        $careers = \App\Models\Career::where('is_active', true)->get();
        
        $seoData = [
            'title' => 'Careers - Join Our Team | Denip Investments Ltd',
            'description' => 'Explore exciting career opportunities with Kenya\'s leading construction company. We offer competitive benefits, training, and career growth.',
            'keywords' => 'careers, jobs, construction jobs Kenya, site engineer, project manager, architect jobs',
            'canonical' => route('careers')
        ];
        
        return view('landing.careers', compact('seoData', 'careers'));
    }
    
    public function careerShow($slug)
    {
        $career = \App\Models\Career::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $seoData = [
            'title' => $career->title . ' - Careers | Denip Investments Ltd',
            'description' => \Str::limit($career->description, 160),
            'keywords' => 'careers, jobs, ' . strtolower($career->title) . ', construction jobs Kenya',
            'canonical' => route('careers.show', $career->slug)
        ];
        
        return view('landing.career-show', compact('career', 'seoData'));
    }
    
    public function careerApply($slug)
    {
        $career = \App\Models\Career::where('slug', $slug)->where('is_active', true)->firstOrFail();
        
        $seoData = [
            'title' => 'Apply for ' . $career->title . ' | Denip Investments Ltd',
            'description' => 'Apply for the ' . $career->title . ' position at Denip Investments Ltd.',
            'keywords' => 'apply, job application, ' . strtolower($career->title),
            'canonical' => route('landing.careers.apply', $career->slug)
        ];
        
        return view('landing.career-apply', compact('career', 'seoData'));
    }

    public function about()
    {
        $seoData = [
            'title' => 'About Us - Leading Construction Company | Denip Investments Ltd',
            'description' => 'Learn about Denip Investments Ltd, Kenya\'s premier construction company with years of experience in residential and commercial projects.',
            'keywords' => 'about us, construction company Kenya, building contractors, Denip Investments',
            'canonical' => route('about')
        ];
        
        return view('landing.about', compact('seoData'));
    }

    public function contact()
    {
        $seoData = [
            'title' => 'Contact Us - Get In Touch | Denip Investments Ltd',
            'description' => 'Contact Denip Investments Ltd for your construction needs. Get quotes, consultations, and professional construction services.',
            'keywords' => 'contact, construction quotes, building consultation, Denip Investments contact',
            'canonical' => route('contact')
        ];
        
        return view('landing.contact', compact('seoData'));
    }

    public function services()
    {
        $services = \App\Models\Service::active()->ordered()->get();
        
        $seoData = [
            'title' => 'Construction Services - Professional Building Solutions | Denip Investments Ltd',
            'description' => 'Comprehensive construction services including residential, commercial, and infrastructure projects. Quality construction solutions in Kenya.',
            'keywords' => 'construction services, building services Kenya, residential construction, commercial construction',
            'canonical' => route('services')
        ];
        
        return view('landing.services', compact('seoData', 'services'));
    }
}