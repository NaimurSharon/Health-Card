<?php

namespace App\Http\Controllers;

use App\Models\Editor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorController extends Controller
{
    public function index(Request $request)
    {
        $query = Editor::with('createdBy');

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $editors = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('backend.editor.index', compact('editors'));
    }

    public function create()
    {
        return view('backend.editor.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:page,post,notice',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        
        // Set published_at if status is published
        if ($request->status === 'published') {
            $data['published_at'] = now();
        }

        Editor::create($data);

        return redirect()->route('admin.editor.index')
            ->with('success', 'Content created successfully.');
    }

    public function show(Editor $editor)
    {
        $editor->load('createdBy');
        return view('backend.editor.show', compact('editor'));
    }

    public function edit(Editor $editor)
    {
        return view('backend.editor.form', compact('editor'));
    }

    public function update(Request $request, Editor $editor)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:page,post,notice',
            'status' => 'required|in:draft,published',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $data = $request->all();
        
        // Set published_at if status changed to published
        if ($request->status === 'published' && $editor->status !== 'published') {
            $data['published_at'] = now();
        }

        $editor->update($data);

        return redirect()->route('admin.editor.index')
            ->with('success', 'Content updated successfully.');
    }

    public function destroy(Editor $editor)
    {
        $editor->delete();

        return redirect()->route('admin.editor.index')
            ->with('success', 'Content deleted successfully.');
    }
}