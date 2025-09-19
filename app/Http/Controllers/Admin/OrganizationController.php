<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    /**
     * Display a listing of organizations for the authenticated user.
     */
    public function index(Request $request)
    {
        $organizations = $request->user()->organizations()->with('elections')->get();
        return view('admin.organizations.index', compact('organizations'));
    }

    /**
     * Store a newly created organization.
     */
    public function store(CreateOrganizationRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $validated['slug'] = Str::slug($validated['name']);

        $organization = Organization::create($validated);

        return back()->with('success', 'Organization created successfully.');
    }

    /**
     * Update the specified organization.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $organization->update($validated);

        return back()->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified organization.
     */
    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);

        $organization->delete();

        return back()->with('success', 'Organization deleted successfully.');
    }
}
