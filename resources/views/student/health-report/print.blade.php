<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Report - {{ $student->name ?? 'Student' }}</title>
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap');
        
        /* Base font classes */
        .inter {
            font-family: 'Inter', 'Arial', sans-serif;
            font-weight: 400;
        }
        
        .bangla {
            font-family: 'Noto Sans Bengali', 'Kalpurush', 'Siyam Rupali', 'SolaimanLipi', 'Arial Unicode MS', sans-serif;
            font-weight: 400;
        }
        
        /* Default body font */
        body {
            font-family: 'Inter', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 1cm;
            }
            .inter {
                font-family: 'Inter', 'Arial', sans-serif !important;
            }
            .bangla {
                font-family: 'Noto Sans Bengali', 'Kalpurush', 'Siyam Rupali', 'SolaimanLipi', 'Arial Unicode MS', sans-serif !important;
            }
        }
        .noto {
          font-family: "Noto Serif Bengali",'Inter', serif;
          font-optical-sizing: auto;
          font-weight: <weight>;
          font-style: normal;
          font-variation-settings:
            "wdth" 100;
        }
        
        .tiro {
          font-family: "Tiro Bangla", 'Inter'!important;
          font-weight: 400;
          font-style: normal;
        }
        
        .tiro-italic {
          font-family: "Tiro Bangla", serif;
          font-weight: 400;
          font-style: italic;
        }
        
        .inter{
            font-family: 'Inter', sans-serif;
        }
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #06AC73;
            padding-bottom: 20px;
        }
        
        .main-title {
            background: linear-gradient(135deg, #06AC73 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(6, 172, 115, 0.2);
        }
        
        .main-title h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .school-name {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border: 2px solid #06AC73;
        }
        
        .school-name h2 {
            margin: 0;
            color: #06AC73;
            font-size: 24px;
            font-weight: 600;
        }
        
        .report-date {
            display: inline-block;
            background: #e6f7f0;
            color: #06AC73;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            margin-top: 10px;
            border: 1px solid #b8f0d9;
        }
        
        /* Legend for Data Status */
        .data-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 15px 0 25px 0;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
        
        .recorded-color {
            background: #06AC73;
        }
        
        .not-recorded-color {
            background: #ef4444;
        }
        
        /* Student Information */
        .student-info {
            background: linear-gradient(to right, #f8f9fa, #f0f7f4);
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .section-title {
            color: #06AC73;
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #06AC73;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .info-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #64748b;
            font-weight: 600;
            min-width: 140px;
        }
        
        .info-value {
            color: #334155;
            font-weight: 500;
            text-align: right;
            flex: 1;
        }
        
        .info-value.not-recorded {
            color: #ef4444;
            font-style: italic;
        }
        
        /* Category Sections */
        .category-section {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }
        
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #06AC73 0%, #059669 100%);
            color: #f5f5f5;
            padding: 18px 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(6, 172, 115, 0.2);
        }
        
        .category-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        
        .category-stats {
            display: flex;
            gap: 15px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 15px;
            border-radius: 20px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Field Grid */
        .field-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .field-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            position: relative;
        }
        
        .field-item.recorded {
            border-left: 4px solid #06AC73;
        }
        
        .field-item.not-recorded {
            border-left: 4px solid #ef4444;
            background: #fef2f2;
        }
        
        .field-label {
            color: #06AC73;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .field-item.not-recorded .field-label {
            color: #ef4444;
        }
        
        .field-value {
            color: #334155;
            font-size: 16px;
            font-weight: 500;
            line-height: 1.5;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .field-value.not-recorded {
            color: #ef4444;
            font-style: italic;
        }
        
        .data-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-recorded {
            background: #d1fae5;
            color: #059669;
        }
        
        .status-not-recorded {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .checkbox-yes {
            color: #10b981;
            font-weight: 700;
        }
        
        .checkbox-no {
            color: #ef4444;
            font-weight: 700;
        }
        
        /* Footer */
        .footer {
            margin-top: 60px;
            padding: 30px 0;
            border-top: 3px solid #e2e8f0;
            text-align: center;
        }
        
        .footer-content {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        
        .footer p {
            margin: 8px 0;
            color: #64748b;
            font-size: 14px;
        }
        
        .footer strong {
            color: #06AC73;
        }
        
        /* Summary Section */
        .summary-section {
            background: linear-gradient(to right, #f0f9ff, #f0f7f4);
            padding: 25px;
            margin: 30px 0;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #06AC73;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 40px;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 30px 0;
            border: 2px dashed #cbd5e1;
        }
        
        .empty-state-icon {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #64748b;
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        
        .empty-state p {
            color: #94a3b8;
            margin: 0;
        }
        
        /* Print Button */
        .print-button {
            background: #06AC73;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin: 10px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            transition: background 0.3s ease;
            font-size: 16px;
        }
        
        .print-button:hover {
            background: #059862;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(6, 172, 115, 0.2);
        }
        
        .button-danger {
            background: #dc3545;
        }
        
        .button-danger:hover {
            background: #c82333;
        }
        
        /* Print-specific styles */
        @media print {
            .field-item.recorded {
                border-left: 3px solid #06AC73 !important;
            }
            
            .field-item.not-recorded {
                border-left: 3px solid #ef4444 !important;
                background: #fef2f2 !important;
            }
            
            .print-button {
                display: none !important;
            }
        }
        
        /* Font weight helpers */
        .font-medium {
            font-weight: 500;
        }
        
        .font-semibold {
            font-weight: 600;
        }
        
        .font-bold {
            font-weight: 700;
        }
    </style>
</head>
<body class="inter">
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="print-button">🖨️ Print This Report</button>
        <button onclick="window.close()" class="print-button button-danger">✖️ Close</button>
    </div>

    <!-- Header Section -->
    <div class="header">
        <div class="main-title">
            @php $headerText = "স্বাস্থ্যকার্ড বিডি রিপোর্ট / HealthCard BD Report"; @endphp
            <h1 class="{{ detectLanguageClass($headerText) }} font-bold">{{ $headerText }}</h1>
        </div>
        
        @if($school)
        <div class="school-name">
            <h2 class="{{ detectLanguageClass($school->name) }} font-semibold">{{ $school->name }}</h2>
        </div>
        @endif
        
        <div class="report-date">
            Report Generated: {{ now()->format('F d, Y') }}
        </div>
    </div>

    <!-- Student Information Section -->
    <div class="student-info">
        <h2 class="section-title">Student Information</h2>
        
        <div class="info-grid">
            <!-- Personal Information -->
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value {{ detectLanguageClass($student->name ?? '') }}">{{ $student->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span class="info-value {{ detectLanguageClass($class->name ?? '') }}">{{ $class->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Section:</span>
                    <span class="info-value {{ detectLanguageClass($section->name ?? '') }}">{{ $section->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date of Birth:</span>
                    <span class="info-value {{ !$student->date_of_birth ? 'not-recorded' : '' }}">
                        {{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : '-' }}
                    </span>
                </div>
                @php $hasRollNumber = isset($student->roll_number) && !empty($student->roll_number); @endphp
                <div class="info-row">
                    <span class="info-label">Roll Number:</span>
                    <span class="info-value {{ !$hasRollNumber ? 'not-recorded' : '' }}">
                        {{ $hasRollNumber ? $student->roll_number : '-' }}
                    </span>
                </div>
            </div>
            
            <!-- Checkup Information -->
            @php 
                $hasCheckupDate = $healthReport && $healthReport->checkup_date;
                $hasCheckedBy = $healthReport && $healthReport->checked_by;
                $hasNextCheckup = $healthReport && $healthReport->next_checkup_date;
                $hasLocation = $healthReport && $healthReport->location;
            @endphp
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Checkup Date:</span>
                    <span class="info-value {{ !$hasCheckupDate ? 'not-recorded' : '' }}">
                        {{ $hasCheckupDate ? \Carbon\Carbon::parse($healthReport->checkup_date)->format('M d, Y') : '-' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Checked By:</span>
                    <span class="info-value {{ detectLanguageClass($healthReport->checked_by ?? '') }} {{ !$hasCheckedBy ? 'not-recorded' : '' }}">
                        {{ $hasCheckedBy ? $healthReport->checked_by : '-' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Next Checkup:</span>
                    <span class="info-value {{ !$hasNextCheckup ? 'not-recorded' : '' }}">
                        {{ $hasNextCheckup ? \Carbon\Carbon::parse($healthReport->next_checkup_date)->format('M d, Y') : '-' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Location:</span>
                    <span class="info-value {{ !$hasLocation ? 'not-recorded' : '' }}">
                        {{ $hasLocation ? $healthReport->location : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    @php
        $totalFields = 0;
        $recordedFields = 0;
        $categoriesWithData = [];
    @endphp

    @if($categories->count() > 0)
        <!-- Calculate Statistics -->
        @foreach($categories as $category)
            @php
                $totalFields += $category->fields->count();
                $categoryRecorded = 0;
                
                foreach($category->fields as $field) {
                    $isRecorded = false;
                    if($healthReport) {
                        foreach ($healthReport->reportData as $data) {
                            if ($data->field && $data->field->id === $field->id && !empty($data->field_value)) {
                                $isRecorded = true;
                                $recordedFields++;
                                $categoryRecorded++;
                                break;
                            }
                        }
                    }
                }
                
                if($category->fields->count() > 0) {
                    $categoriesWithData[] = [
                        'category' => $category,
                        'total' => $category->fields->count(),
                        'recorded' => $categoryRecorded
                    ];
                }
            @endphp
        @endforeach
        
        <!-- Health Data Sections -->
        @foreach($categories as $category)
            @php
                $categoryRecorded = 0;
                $categoryTotal = $category->fields->count();
                
                if($healthReport) {
                    foreach($category->fields as $field) {
                        foreach ($healthReport->reportData as $data) {
                            if ($data->field && $data->field->id === $field->id && !empty($data->field_value)) {
                                $categoryRecorded++;
                                break;
                            }
                        }
                    }
                }
            @endphp
            
            <div class="category-section">
                <div class="category-header">
                    <h3 class="category-title {{ detectLanguageClass($category->name) }}">{{ $category->name }}</h3>
                </div>
                
                <div class="field-grid">
                    @foreach($category->fields as $field)
                        @php
                            $value = null;
                            $isRecorded = false;
                            
                            if($healthReport) {
                                foreach ($healthReport->reportData as $data) {
                                    if ($data->field && $data->field->id === $field->id) {
                                        $value = $data->field_value;
                                        $isRecorded = !empty($value);
                                        break;
                                    }
                                }
                            }
                        @endphp
                        
                        <div class="field-item {{ $isRecorded ? 'recorded' : 'not-recorded' }}">
                            <span class="field-label {{ detectLanguageClass($field->label) }}">{{ $field->label }}</span>
                            <div class="field-value {{ !$isRecorded ? 'not-recorded' : '' }}">
                                @if($isRecorded)
                                    @if($field->field_type === 'checkbox')
                                        <span class="{{ $value == '1' ? 'checkbox-yes' : 'checkbox-no' }}">
                                            {{ $value == '1' ? '✓ Yes' : '✗ No' }}
                                        </span>
                                    @elseif($field->field_type === 'date')
                                        {{ \Carbon\Carbon::parse($value)->format('M d, Y') }}
                                    @else
                                        <span class="{{ detectLanguageClass($value) }}">{{ $value }}</span>
                                    @endif
                                @else
                                @endif
                                <span class="data-status {{ $isRecorded ? '' : 'status-not-recorded' }}">
                                    {{ $isRecorded ? '' : 'Not Recorded' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <h3>No Health Categories Available</h3>
            <p>No health categories have been configured in the system.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-content">
            <p>This is an official health report generated on <strong>{{ now()->format('F d, Y \a\t h:i A') }}</strong></p>
            @if($school)
            <p class="{{ detectLanguageClass($school->name) }}">Issued by: <strong>{{ $school->name }}</strong></p>
            @endif
            <p style="font-size: 12px; color: #94a3b8; margin-top: 15px;">
                Confidential Document • Unauthorized reproduction prohibited
            </p>
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        // window.onload = function() { window.print(); }
        
        // Enhance print functionality
        document.querySelector('.print-button').addEventListener('click', function() {
            window.print();
        });
        
        // Add keyboard shortcut for printing (Ctrl+P)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>