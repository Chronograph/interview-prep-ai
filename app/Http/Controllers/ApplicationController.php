<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $applications = $user->applications()
            ->orderBy('application_date', 'desc')
            ->get();

        return response()->json([
            'applications' => $applications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Create form data']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'position_title' => 'required|string|max:255',
            'job_url' => 'nullable|url|max:500',
            'status' => ['required', Rule::in(['applied', 'screening', 'interview', 'offer', 'rejected', 'withdrawn'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'application_date' => 'required|date',
            'expected_response_date' => 'nullable|date|after:application_date',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0|gte:salary_min',
            'location' => 'nullable|string|max:255',
            'work_type' => ['nullable', Rule::in(['remote', 'hybrid', 'onsite'])],
            'notes' => 'nullable|string|max:1000',
            'interview_stages' => 'nullable|array',
            'interview_stages.*' => 'string|max:100',
            'contacts' => 'nullable|array',
            'requirements' => 'nullable|array',
            'is_favorite' => 'boolean'
        ]);

        $validated['user_id'] = Auth::id();

        Application::create($validated);

        return redirect()->route('analytics.applications.index')
            ->with('success', 'Application added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        $this->authorize('view', $application);

        return response()->json([
            'application' => $application
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        $this->authorize('update', $application);

        return response()->json([
            'application' => $application
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'position_title' => 'required|string|max:255',
            'job_url' => 'nullable|url|max:500',
            'status' => ['required', Rule::in(['applied', 'screening', 'interview', 'offer', 'rejected', 'withdrawn'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'application_date' => 'required|date',
            'expected_response_date' => 'nullable|date|after:application_date',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|min:0|gte:salary_min',
            'location' => 'nullable|string|max:255',
            'work_type' => ['nullable', Rule::in(['remote', 'hybrid', 'onsite'])],
            'notes' => 'nullable|string|max:1000',
            'interview_stages' => 'nullable|array',
            'interview_stages.*' => 'string|max:100',
            'contacts' => 'nullable|array',
            'requirements' => 'nullable|array',
            'is_favorite' => 'boolean'
        ]);

        $application->update($validated);

        return redirect()->route('analytics.applications.index')
            ->with('success', 'Application updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        $this->authorize('delete', $application);

        $application->delete();

        return redirect()->route('analytics.applications.index')
            ->with('success', 'Application deleted successfully!');
    }

    /**
     * Update application status
     */
    public function updateStatus(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['applied', 'screening', 'interview', 'offer', 'rejected', 'withdrawn'])],
            'notes' => 'nullable|string|max:1000'
        ]);

        $application->updateStatus($validated['status'], $validated['notes'] ?? null);

        return response()->json([
            'message' => 'Status updated successfully',
            'application' => $application->fresh()
        ]);
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite(Application $application)
    {
        $this->authorize('update', $application);

        $application->update([
            'is_favorite' => !$application->is_favorite
        ]);

        return response()->json([
            'message' => 'Favorite status updated',
            'is_favorite' => $application->is_favorite
        ]);
    }
}
