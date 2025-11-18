<?php

namespace App\Http\Controllers;

use App\Models\HealthTip;
use Illuminate\Http\Request;

class HealthTipController extends Controller
{
    public function index(Request $request)
    {
        $query = HealthTip::with('createdBy');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $healthTips = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('backend.health-tips.index', compact('healthTips'));
    }

    public function create()
    {
        return view('backend.health-tips.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'target_audience' => 'required|in:students,teachers,parents,all',
            'status' => 'required|in:published,draft', // Fixed to match database
        ]);

        HealthTip::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'target_audience' => $request->target_audience,
            'status' => $request->status,
            'published_by' => auth()->id(),
        ]);

        return redirect()->route('admin.health-tips.index')
            ->with('success', 'Health tip created successfully.');
    }

    public function show(HealthTip $healthTip)
    {
        $healthTip->load('createdBy');
        return view('backend.health-tips.show', compact('healthTip'));
    }

    public function edit(HealthTip $healthTip)
    {
        return view('backend.health-tips.form', compact('healthTip'));
    }

    public function update(Request $request, HealthTip $healthTip)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'target_audience' => 'required|in:students,teachers,parents,all',
            'status' => 'required|in:published,draft', // Fixed to match database
        ]);

        $healthTip->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'target_audience' => $request->target_audience,
            'status' => $request->status,
            // published_by should not be updated on edit
        ]);

        return redirect()->route('admin.health-tips.index')
            ->with('success', 'Health tip updated successfully.');
    }

    public function destroy(HealthTip $healthTip)
    {
        $healthTip->delete();

        return redirect()->route('admin.health-tips.index')
            ->with('success', 'Health tip deleted successfully.');
    }
}