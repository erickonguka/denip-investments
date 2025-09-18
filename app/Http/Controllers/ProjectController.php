<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['client', 'creator', 'category'])->latest()->paginate(15);
        $clients = Client::where('status', 'active')->get();
        $users = User::all();
        $categories = Category::where('is_active', true)->get();
        return view('projects.index', compact('projects', 'clients', 'users', 'categories'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        return view('projects.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'assigned_users' => 'nullable|array',
            'is_public' => 'nullable|in:0,1,true,false',
            'media' => 'nullable|array',
            'media.*' => 'file|max:10240|mimes:jpeg,jpg,png,gif,webp,avif,pdf,doc,docx,pptx,txt,csv,xls,xlsx',
        ]);

        // Convert is_public to boolean
        if (isset($validated['is_public'])) {
            $validated['is_public'] = in_array($validated['is_public'], ['1', 'true', true], true);
        }
        
        if ($validated['is_public'] ?? false) {
            $validated['public_token'] = Str::random(32);
        }

        // Handle file uploads
        if ($request->hasFile('media')) {
            $mediaFiles = [];
            foreach ($request->file('media') as $file) {
                $path = $file->store('projects', 'public');
                $mediaFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
            $validated['media'] = $mediaFiles;
        }

        try {
            $validated['created_by'] = auth()->id();
            Project::create($validated);
            return response()->json(['success' => true, 'message' => 'Project created successfully']);
        } catch (\Exception $e) {
            \Log::error('Project creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create project: ' . $e->getMessage()], 500);
        }
    }

    public function show(Project $project)
    {
        $project->load(['client', 'invoices', 'proposals', 'quotations']);
        $clients = Client::where('status', 'active')->get();
        $users = User::all();
        $categories = Category::where('is_active', true)->get();
        
        // Get assigned users
        $assignedUsers = collect();
        if ($project->assigned_users) {
            $assignedUsers = User::whereIn('id', $project->assigned_users)->get();
        }
        
        return view('projects.show', compact('project', 'clients', 'users', 'categories', 'assignedUsers'));
    }

    public function edit(Project $project)
    {
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $project
            ]);
        }
        
        $clients = Client::where('status', 'active')->get();
        return view('projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project)
    {
        // Handle AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return $this->handleAjaxUpdate($request, $project);
        }
        
        // Handle regular form submissions
        return $this->handleFormUpdate($request, $project);
    }
    
    private function handleAjaxUpdate(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:planning,active,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
            'is_public' => 'nullable|in:0,1,true,false',
            'assigned_users' => 'nullable|array',
            'media' => 'nullable|array',
            'media.*' => 'file|max:10240|mimes:jpeg,jpg,png,gif,webp,avif,pdf,doc,docx,pptx,txt,csv,xls,xlsx',
        ]);
        
        // Convert is_public to boolean
        if (isset($validated['is_public'])) {
            $validated['is_public'] = in_array($validated['is_public'], ['1', 'true', true], true);
        }

        // Handle public token generation
        if ($validated['is_public'] ?? false) {
            if (!$project->public_token) {
                $validated['public_token'] = Str::random(32);
            }
        } else {
            $validated['is_public'] = false;
            $validated['public_token'] = null;
        }

        // Get current existing media
        $currentMedia = $project->media ?? [];
        
        // Handle removed media
        $removedIndices = [];
        if ($request->has('removed_media')) {
            $removedIndices = json_decode($request->input('removed_media'), true) ?? [];
        }
        
        // Filter out removed media
        $remainingMedia = [];
        foreach ($currentMedia as $index => $media) {
            if (!in_array($index, $removedIndices)) {
                $remainingMedia[] = $media;
            }
        }
        
        // Handle new file uploads
        $newMediaFiles = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('projects', 'public');
                $newMediaFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ];
            }
        }
        
        $validated['media'] = array_merge($remainingMedia, $newMediaFiles);

        try {
            $project->update($validated);
            return response()->json(['success' => true, 'message' => 'Project updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Project update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update project: ' . $e->getMessage()], 500);
        }
    }
    
    private function handleFormUpdate(Request $request, Project $project)
    {
        // Handle regular form update (redirect back)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:planning,active,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
        ]);
        
        $project->update($validated);
        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['success' => true, 'message' => 'Project deleted successfully']);
    }

    public function publicView($slug)
    {
        $project = Project::where('slug', $slug)
            ->where('is_public', true)
            ->with('client')
            ->firstOrFail();
            
        // Get assigned users
        $assignedUsers = collect();
        if ($project->assigned_users) {
            $assignedUsers = User::whereIn('id', $project->assigned_users)->get();
        }
            
        return view('projects.single', compact('project', 'assignedUsers'));
    }

    public function publicViewByToken($token)
    {
        $project = Project::where('public_token', $token)
            ->where('is_public', true)
            ->with('client')
            ->firstOrFail();
            
        // Get assigned users
        $assignedUsers = collect();
        if ($project->assigned_users) {
            $assignedUsers = User::whereIn('id', $project->assigned_users)->get();
        }
            
        return view('projects.single', compact('project', 'assignedUsers'));
    }

    public function careers()
    {
        return view('landing.careers');
    }

    public function landingIndex()
    {
        $projects = Project::where('is_public', true)
            ->with(['client'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('landing.index', compact('projects'));
    }

    public function landingPage()
    {
        $projects = Project::where('is_public', true)
            ->with(['client'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('landing.projects', compact('projects'));
    }
}