<?php

namespace App\Http\Controllers;

use App\Models\WorkReport;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class WorkReportController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkReport::with(['staff', 'createdBy']);
        
        // Filter by date range
        if ($request->filled(['from_date', 'to_date'])) {
            $query->dateRange($request->from_date, $request->to_date);
        } else {
            // Default to current month
            $query->dateRange(now()->startOfMonth(), now()->endOfMonth());
        }
        
        // Filter by staff
        if ($request->filled('staff_id')) {
            $query->byStaff($request->staff_id);
        }
        
        // Filter by work type
        if ($request->filled('work_type')) {
            $query->byWorkType($request->work_type);
        }

        $workReports = $query->latest('work_date')->paginate(20);
        $staff = User::where('role', '!=', 'admin')->where('status', 'active')->get();
        $workTypes = $this->getWorkTypes();

        return view('backend.work-reports.index', compact('workReports', 'staff', 'workTypes'));
    }

    public function create()
    {
        $staff = User::where('role', '!=', 'admin')->where('status', 'active')->get();
        $workTypes = $this->getWorkTypes();
        $taskStatuses = $this->getTaskStatuses();
        
        return view('backend.work-reports.form', compact('staff', 'workTypes', 'taskStatuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'work_date' => 'required|date',
            'work_description' => 'required|string|max:1000',
            'task_status' => 'required|in:in_progress,completed,pending,blocked',
            'notes' => 'nullable|string|max:500'
        ]);

        $validated['created_by'] = auth()->id();

        WorkReport::create($validated);

        return redirect()->route('admin.work-reports.index')
            ->with('success', 'Work report added successfully.');
    }

    public function show(WorkReport $workReport)
    {
        $workReport->load(['staff', 'createdBy']);
        
        // Get previous and next reports for navigation
        $previousReport = WorkReport::where('id', '<', $workReport->id)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextReport = WorkReport::where('id', '>', $workReport->id)
            ->orderBy('id', 'asc')
            ->first();
    
        return view('backend.work-reports.show', compact('workReport', 'previousReport', 'nextReport'));
    }

    public function edit(WorkReport $workReport)
    {
        $staff = User::where('role', '!=', 'admin')->where('status', 'active')->get();
        $workTypes = $this->getWorkTypes();
        $taskStatuses = $this->getTaskStatuses();
        
        return view('backend.work-reports.form', compact('workReport', 'staff', 'workTypes', 'taskStatuses'));
    }

    public function update(Request $request, WorkReport $workReport)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'work_date' => 'required|date',
            'work_description' => 'required|string|max:1000',
            'task_status' => 'required|in:in_progress,completed,pending,blocked',
            'notes' => 'nullable|string|max:500'
        ]);

        $workReport->update($validated);

        return redirect()->route('admin.work-reports.index')
            ->with('success', 'Work report updated successfully.');
    }

    public function destroy(WorkReport $workReport)
    {
        $workReport->delete();

        return redirect()->route('admin.work-reports.index')
            ->with('success', 'Work report deleted successfully.');
    }

    public function reports(Request $request)
    {
        $startDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('to_date', now()->endOfMonth()->format('Y-m-d'));
        $staffId = $request->get('staff_id');

        $query = WorkReport::with('staff')
            ->dateRange($startDate, $endDate);

        if ($staffId) {
            $query->byStaff($staffId);
        }

        $workReports = $query->get();
        $staff = User::where('role', '!=', 'admin')->where('status', 'active')->get();

        // Summary statistics
        $totalHours = $workReports->sum('hours_worked');
        $totalTasks = $workReports->count();
        $completedTasks = $workReports->where('task_status', 'completed')->count();
        
        // Hours by work type
        $hoursByType = $workReports->groupBy('work_type')->map(function ($reports) {
            return $reports->sum('hours_worked');
        });

        // Hours by staff
        $hoursByStaff = $workReports->groupBy('staff_id')->map(function ($reports) {
            return [
                'staff' => $reports->first()->staff,
                'total_hours' => $reports->sum('hours_worked'),
                'task_count' => $reports->count()
            ];
        });

        return view('backend.work-reports.reports', compact(
            'workReports', 'staff', 'startDate', 'endDate', 'staffId',
            'totalHours', 'totalTasks', 'completedTasks', 'hoursByType', 'hoursByStaff'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('to_date', now()->endOfMonth()->format('Y-m-d'));
        $staffId = $request->get('staff_id');
        $reportType = $request->get('report_type', 'detailed'); // detailed or summary

        $query = WorkReport::with('staff')
            ->dateRange($startDate, $endDate);

        if ($staffId) {
            $query->byStaff($staffId);
            $staff = User::find($staffId);
        } else {
            $staff = null;
        }

        $workReports = $query->get();

        if ($reportType === 'summary') {
            $pdf = PDF::loadView('backend.work-reports.exports.summary-pdf', compact(
                'workReports', 'startDate', 'endDate', 'staff'
            ));
        } else {
            $pdf = PDF::loadView('backend.work-reports.exports.detailed-pdf', compact(
                'workReports', 'startDate', 'endDate', 'staff'
            ));
        }

        $filename = 'work-report-' . $startDate . '-to-' . $endDate;
        if ($staff) {
            $filename .= '-' . str_slug($staff->first_name);
        }
        $filename .= '.pdf';

        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        // You can implement Excel export using Laravel Excel package
        // This is a basic implementation
        $startDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('to_date', now()->endOfMonth()->format('Y-m-d'));
        $staffId = $request->get('staff_id');

        // Return a CSV file as a simple implementation
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="work-reports.csv"',
        ];

        $callback = function() use ($startDate, $endDate, $staffId) {
            $query = WorkReport::with('staff')
                ->dateRange($startDate, $endDate);

            if ($staffId) {
                $query->byStaff($staffId);
            }

            $workReports = $query->get();

            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            fputcsv($file, ['Date', 'Staff', 'Project', 'Work Description', 'Hours', 'Work Type', 'Status', 'Notes']);

            foreach ($workReports as $report) {
                fputcsv($file, [
                    $report->work_date->format('Y-m-d'),
                    $report->staff->first_name . ' ' . $report->staff->last_name,
                    $report->project_name,
                    $report->work_description,
                    $report->hours_worked,
                    ucfirst($report->work_type),
                    ucfirst(str_replace('_', ' ', $report->task_status)),
                    $report->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getWorkTypes()
    {
        return [
            'development' => 'Development',
            'design' => 'Design',
            'testing' => 'Testing',
            'meeting' => 'Meeting',
            'documentation' => 'Documentation',
            'support' => 'Support',
            'other' => 'Other'
        ];
    }

    private function getTaskStatuses()
    {
        return [
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'pending' => 'Pending',
            'blocked' => 'Blocked'
        ];
    }
}