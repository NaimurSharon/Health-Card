<!-- Health Report Card Component -->
<div class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                @if($report->student->user->profile_image)
                    <img class="h-12 w-12 rounded-full object-cover" 
                         src="{{ asset('public/storage/' . $report->student->user->profile_image) }}" 
                         alt="{{ $report->student->user->name }}">
                @else
                    <i class="fas fa-user text-blue-600 text-lg"></i>
                @endif
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-900">{{ $report->student->user->name }}</h4>
                <p class="text-sm text-gray-500">{{ $report->student->student_id }}</p>
            </div>
        </div>
        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
            {{ $report->checkup_date->format('M d') }}
        </span>
    </div>
    
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <p class="text-xs text-gray-500">Class</p>
            <p class="text-sm font-medium text-gray-900">{{ $report->student->class->name ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500">Roll No</p>
            <p class="text-sm font-medium text-gray-900">{{ $report->student->roll_number ?? 'N/A' }}</p>
        </div>
    </div>
    
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <span class="text-xs text-gray-500">By {{ $report->checked_by }}</span>
        <div class="flex space-x-2">
            <a href="{{ route('admin.health-reports.student', $report->student->user) }}" 
               class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                View
            </a>
            <a href="{{ route('admin.health-reports.edit', $report) }}" 
               class="text-green-600 hover:text-green-800 text-sm font-medium transition-colors">
                Edit
            </a>
        </div>
    </div>
</div>