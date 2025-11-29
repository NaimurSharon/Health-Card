<?php

namespace App\Http\Controllers;

use App\Models\CityCorporationNotice;
use App\Models\School;
use Illuminate\Http\Request;

class CityCorporationNoticeController extends Controller
{
    public function index()
    {
        $notices = CityCorporationNotice::with('publishedBy')->latest()->paginate(10);
        $schools = School::where('status', 'active')->get();
        
        return view('backend.city-corporation-notices.index', compact('notices', 'schools'));
    }

    public function create()
    {
        $notice = null;
        $schools = School::where('status', 'active')->get();
        return view('backend.city-corporation-notices.form', compact('notice', 'schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:high,medium,low',
            'target_type' => 'required|in:all_schools,specific_schools',
            'target_schools' => 'required_if:target_type,specific_schools|array',
            'target_roles' => 'required|array',
            'expiry_date' => 'required|date',
            'status' => 'required|in:published,draft',
        ]);

        CityCorporationNotice::create([
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority,
            'target_type' => $request->target_type,
            'target_schools' => $request->target_type === 'specific_schools' ? $request->target_schools : null,
            'target_roles' => $request->target_roles,
            'expiry_date' => $request->expiry_date,
            'published_by' => auth()->id(),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.city-corporation-notices.index')
            ->with('success', 'City Corporation notice created successfully.');
    }

    public function show(CityCorporationNotice $cityCorporationNotice)
    {
        $cityCorporationNotice->load('publishedBy');
        $schools = School::whereIn('id', $cityCorporationNotice->target_schools ?? [])->get();
        
        return view('backend.city-corporation-notices.show', compact('cityCorporationNotice', 'schools'));
    }

    public function edit(CityCorporationNotice $cityCorporationNotice)
    {
        $schools = School::where('status', 'active')->get();
        return view('backend.city-corporation-notices.form', compact('cityCorporationNotice', 'schools'));
    }

    public function update(Request $request, CityCorporationNotice $cityCorporationNotice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:high,medium,low',
            'target_type' => 'required|in:all_schools,specific_schools',
            'target_schools' => 'required_if:target_type,specific_schools|array',
            'target_roles' => 'required|array',
            'expiry_date' => 'required|date',
            'status' => 'required|in:published,draft',
        ]);

        $cityCorporationNotice->update([
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority,
            'target_type' => $request->target_type,
            'target_schools' => $request->target_type === 'specific_schools' ? $request->target_schools : null,
            'target_roles' => $request->target_roles,
            'expiry_date' => $request->expiry_date,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.city-corporation-notices.index')
            ->with('success', 'City Corporation notice updated successfully.');
    }

    public function destroy(CityCorporationNotice $cityCorporationNotice)
    {
        $cityCorporationNotice->delete();

        return redirect()->route('admin.city-corporation-notices.index')
            ->with('success', 'City Corporation notice deleted successfully.');
    }

    // Get notices for specific school (API endpoint for schools)
    public function getSchoolNotices($schoolId)
    {
        $notices = CityCorporationNotice::with('publishedBy')
            ->active()
            ->forSchool($schoolId)
            ->latest()
            ->get();

        return response()->json($notices);
    }

    public function getPublicNotices($schoolId, $role)
    {
        $notices = CityCorporationNotice::with('publishedBy')
            ->active()
            ->forSchool($schoolId)
            ->forRoles([$role])
            ->latest()
            ->paginate(10);

        return view('frontend.city-corporation-notices.index', compact('notices'));
    }
}