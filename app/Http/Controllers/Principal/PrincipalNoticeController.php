<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipalNoticeController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        $notices = Notice::where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('principal.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('principal.notices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'priority' => 'required|in:high,medium,low',
            'target_roles' => 'required|array',
            'target_roles.*' => 'in:student,teacher,parent,staff',
            'expiry_date' => 'required|date|after:today',
        ]);

        $school = auth()->user()->school;

        Notice::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'priority' => $request->input('priority'),
            'target_roles' => $request->input('target_roles'),
            'expiry_date' => $request->input('expiry_date'),
            'status' => $request->input('status'),
            'published_by' => Auth::id(),
            'school_id' => $school->id,
        ]);

        return redirect()->route('principal.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function edit($id)
    {
        $notice = Notice::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        return view('principal.notices.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'priority' => 'required|in:high,medium,low',
            'target_roles' => 'required|array',
            'target_roles.*' => 'in:student,teacher,parent,staff',
            'expiry_date' => 'required|date|after:today',
        ]);

        $notice->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'status' => $request->input('status'),
            'priority' => $request->input('priority'),
            'target_roles' => $request->input('target_roles'),
            'expiry_date' => $request->input('expiry_date'),
        ]);

        return redirect()->route('principal.notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy($id)
    {
        $notice = Notice::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        $notice->delete();

        return redirect()->route('principal.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }

    public function publish($id)
    {
        $notice = Notice::where('school_id', auth()->user()->school->id)
            ->findOrFail($id);

        $notice->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->route('principal.notices.index')
            ->with('success', 'Notice published successfully.');
    }
}