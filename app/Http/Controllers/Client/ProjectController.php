<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Project;
use App\Models\Category;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    
    public function index()
    {
        $user = Auth::user();
        $client = $user->client;
        
        if (!$client) {
            $projects = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                8,
                1,
                ['path' => request()->url()]
            );
        } else {
            $projects = Project::where('client_id', $client->id)
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->paginate(8);
        }
            
        return view('client.projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load([
            'invoices' => function($query) {
                $query->whereIn('status', ['sent', 'paid', 'overdue']);
            },
            'proposals' => function($query) {
                $query->whereIn('status', ['sent', 'accepted', 'rejected']);
            }
        ]);
        return view('client.projects.show', compact('project'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('client.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'start_date' => 'nullable|date|after_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'budget' => 'nullable|numeric|min:0',
                'media.*' => 'nullable|file|max:10240|mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Please check the form for errors'
            ], 422);
        }

        // Get or create client record for the authenticated user
        $user = Auth::user();
        $client = $user->client;
        
        if (!$client) {
            $client = \App\Models\Client::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'company' => $user->company,
                'email' => $user->email,
                'phone' => $user->phone,
                'type' => 'individual',
                'status' => 'active'
            ]);
        }

        $validated['client_id'] = $client->id;
        $validated['status'] = 'planning';
        $validated['progress'] = 0;
        $validated['created_by'] = auth()->id();

        // Handle media uploads
        if ($request->hasFile('media')) {
            $mediaFiles = [];
            foreach ($request->file('media') as $file) {
                $path = $file->store('projects', 'public');
                $mediaFiles[] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ];
            }
            $validated['media'] = $mediaFiles;
        }

        try {
            $project = Project::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully',
                'redirect' => route('client.projects.show', $project)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $categories = Category::where('is_active', true)->get();
        return view('client.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'start_date' => 'nullable|date|after_or_equal:today',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'budget' => 'nullable|numeric|min:0',
                'media.*' => 'nullable|file|max:10240|mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx',
                'removed_media' => 'nullable|string'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Please check the form for errors'
            ], 422);
        }

        // Handle removed media
        if ($request->removed_media) {
            $removedIndices = json_decode($request->removed_media, true);
            $currentMedia = $project->media ?? [];
            
            foreach ($removedIndices as $index) {
                if (isset($currentMedia[$index])) {
                    Storage::disk('public')->delete($currentMedia[$index]['path']);
                    unset($currentMedia[$index]);
                }
            }
            $validated['media'] = array_values($currentMedia);
        } else {
            $validated['media'] = $project->media ?? [];
        }

        // Handle new media uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('projects', 'public');
                $validated['media'][] = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ];
            }
        }

        try {
            $project->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully',
                'redirect' => route('client.projects.show', $project)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        
        // Delete associated media files
        if ($project->media) {
            foreach ($project->media as $media) {
                Storage::disk('public')->delete($media['path']);
            }
        }
        
        $project->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
    }
}