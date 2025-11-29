<?php
// app/Http/Controllers/StaffController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SalaryPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('role', '!=', 'admin')->paginate(20);
        return view('backend.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('backend.staff.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'role' => 'required|in:accountant,manager,staff',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'required|date',
            'status' => 'required|in:active,inactive'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully.');
    }

    public function show(User $staff)
    {
        $salaryPayments = SalaryPayment::where('staff_id', $staff->id)
            ->latest()
            ->paginate(10);

        return view('backend.staff.show', compact('staff', 'salaryPayments'));
    }
    
    public function destroy(User $staff)
    {
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    public function edit(User $staff)
    {
        return view('backend.staff.form', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $staff->id . '|max:50',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'role' => 'required|in:accountant,manager,staff',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'hire_date' => 'required|date',
            'status' => 'required|in:active,inactive'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function salaryPayments(Request $request)
    {
        $query = SalaryPayment::with(['staff', 'paidBy']);
        
        if ($request->filled('month_year')) {
            $query->where('month_year', $request->month_year);
        }

        $payments = $query->latest()->paginate(20);

        return view('backend.staff.salary-payments', compact('payments'));
    }

    public function createSalaryPayment()
    {
        $staff = User::where('role', '!=', 'admin')->where('status', 'active')->get();
        return view('backend.staff.create-salary-payment', compact('staff'));
    }

    public function storeSalaryPayment(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'payment_date' => 'required|date',
            'month_year' => 'required|string|max:7',
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer',
            'notes' => 'nullable|string'
        ]);

        $validated['net_salary'] = $validated['basic_salary'] + ($validated['bonus'] ?? 0) - ($validated['deductions'] ?? 0);
        $validated['paid_by'] = auth()->id();
        $validated['status'] = 'paid';

        SalaryPayment::create($validated);

        return redirect()->route('admin.salary-payments.index')
            ->with('success', 'Salary payment recorded successfully.');
    }
}