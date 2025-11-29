<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with('publishedBy')->latest()->paginate(10);
        return view('backend.notices.index', compact('notices'));
    }

    public function create()
    {
        $notice = null;
        return view('backend.notices.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:high,medium,low',
            'target_roles' => 'required|array',
            'expiry_date' => 'required|date',
            'status' => 'required|in:published,draft',
        ]);

        Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority,
            'target_roles' => $request->target_roles,
            'expiry_date' => $request->expiry_date,
            'published_by' => auth()->id(),
            'status' => $request->status,
        ]);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function show(Notice $notice)
    {
        $notice->load('publishedBy');
        return view('backend.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        return view('backend.notices.form', compact('notice'));
    }
    
    public function diary()
    {
        $notices = Notice::with('publishedBy')->latest()->paginate(10);
        return view('backend.notices.diary', compact('notices'));
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:high,medium,low',
            'target_roles' => 'required|array',
            'expiry_date' => 'required|date',
            'status' => 'required|in:published,draft',
        ]);

        $notice->update($request->all());

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice updated successfully.');
    }
    
    public function homepage()
    {
        $notices = Notice::with('publishedBy')
            ->where('status', 'published')
            ->where('expiry_date', '>=', now())
            ->latest()
            ->paginate(10);

        return view('backend.notices.homepage', compact('notices'));
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }
}