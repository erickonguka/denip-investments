<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\CareerApplication;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::withCount('applications')->latest()->paginate(15);
        return view('careers.index', compact('careers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full-time,part-time,contract,internship',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        Career::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Career position created successfully'
        ]);
    }

    public function edit(Career $career)
    {
        return response()->json([
            'success' => true,
            'data' => $career
        ]);
    }

    public function update(Request $request, Career $career)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full-time,part-time,contract,internship',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $career->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Career position updated successfully'
        ]);
    }

    public function destroy(Career $career)
    {
        $career->delete();

        return response()->json([
            'success' => true,
            'message' => 'Career position deleted successfully'
        ]);
    }

    public function applications(Career $career)
    {
        $applications = $career->applications()->latest()->paginate(15);
        return view('careers.applications', compact('career', 'applications'));
    }

    public function updateApplicationStatus(Request $request, CareerApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,rejected,hired',
            'admin_notes' => 'nullable|string'
        ]);

        $application->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully'
        ]);
    }
}