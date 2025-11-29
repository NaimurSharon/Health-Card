<?php

namespace App\Http\Controllers;

use App\Models\IndividualMember;
use Illuminate\Http\Request;

class IndividualMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = IndividualMember::query();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('membership_type') && $request->membership_type) {
            $query->where('membership_type', $request->membership_type);
        }

        $members = $query->orderBy('name')->paginate(20);
        return view('backend.individual-members.index', compact('members'));
    }

    public function create()
    {
        return view('backend.individual-members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:individual_members',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'membership_type' => 'required|in:regular,premium,vip',
            'membership_date' => 'required|date',
            'expiry_date' => 'required|date|after:membership_date',
            'status' => 'required|in:active,inactive,expired',
        ]);

        IndividualMember::create($request->all());

        return redirect()->route('admin.individual-members.index')
            ->with('success', 'Individual member created successfully.');
    }

    public function show(IndividualMember $individualMember)
    {
        return view('backend.individual-members.show', compact('individualMember'));
    }

    public function edit(IndividualMember $individualMember)
    {
        return view('backend.individual-members.edit', compact('individualMember'));
    }

    public function update(Request $request, IndividualMember $individualMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:individual_members,email,' . $individualMember->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'membership_type' => 'required|in:regular,premium,vip',
            'membership_date' => 'required|date',
            'expiry_date' => 'required|date|after:membership_date',
            'status' => 'required|in:active,inactive,expired',
        ]);

        $individualMember->update($request->all());

        return redirect()->route('admin.individual-members.index')
            ->with('success', 'Individual member updated successfully.');
    }

    public function destroy(IndividualMember $individualMember)
    {
        $individualMember->delete();

        return redirect()->route('admin.individual-members.index')
            ->with('success', 'Individual member deleted successfully.');
    }
}