<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notice;

class SchoolNoticesController extends Controller
{
    public function index()
    {
        $notices = Notice::where('status', 'published')
            ->where(function($query) {
                $query->where('target_roles', 'like', '%student%')
                      ->orWhere('target_roles', 'like', '%all%');
            })
            ->with('publishedBy') // Eager load the publishedBy relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics
        $highPriorityCount = Notice::where('status', 'published')
            ->where('priority', 'high')
            ->where(function($query) {
                $query->where('target_roles', 'like', '%student%')
                      ->orWhere('target_roles', 'like', '%all%');
            })
            ->count();

        $thisWeekCount = Notice::where('status', 'published')
            ->where(function($query) {
                $query->where('target_roles', 'like', '%student%')
                      ->orWhere('target_roles', 'like', '%all%');
            })
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();

        return view('student.school-notices.index', compact(
            'notices',
            'highPriorityCount',
            'thisWeekCount'
        ));
    }

    public function show($id)
    {
        $notice = Notice::where('id', $id)
            ->where('status', 'published')
            ->where(function($query) {
                $query->where('target_roles', 'like', '%student%')
                      ->orWhere('target_roles', 'like', '%all%');
            })
            ->with('publishedBy') // Eager load the publishedBy relationship
            ->firstOrFail();

        // Get related notices (same priority from last 30 days)
        $relatedNotices = Notice::where('status', 'published')
            ->where('id', '!=', $notice->id)
            ->where('priority', $notice->priority)
            ->where(function($query) {
                $query->where('target_roles', 'like', '%student%')
                      ->orWhere('target_roles', 'like', '%all%');
            })
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('student.school-notices.show', compact(
            'notice',
            'relatedNotices'
        ));
    }
}