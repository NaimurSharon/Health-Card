<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homework Details - {{ $homework->homework_title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 25px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #06AC73;
            padding-bottom: 25px;
        }
        .school-name {
            font-size: 28px;
            font-weight: bold;
            color: #06AC73;
            margin-bottom: 8px;
        }
        .document-title {
            font-size: 22px;
            font-weight: bold;
            margin: 15px 0;
            color: #2c3e50;
        }
        .student-info {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }
        .print-date {
            font-size: 14px;
            color: #777;
            margin-top: 10px;
        }
        .homework-details {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 5px solid #06AC73;
        }
        .detail-row {
            margin-bottom: 15px;
            display: flex;
        }
        .detail-label {
            font-weight: bold;
            color: #2c3e50;
            width: 150px;
            flex-shrink: 0;
        }
        .detail-value {
            color: #555;
            flex-grow: 1;
        }
        .homework-content {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .homework-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            border-bottom: 2px solid #06AC73;
            padding-bottom: 10px;
        }
        .homework-description {
            color: #555;
            font-size: 16px;
            white-space: pre-line;
            line-height: 1.8;
        }
        .attachments-section {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .attachments-title {
            font-weight: bold;
            color: #27ae60;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .attachment-item {
            background: white;
            padding: 10px 15px;
            margin: 8px 0;
            border-radius: 5px;
            border-left: 3px solid #27ae60;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-completed {
            background: #fff3cd;
            color: #856404;
        }
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .due-date {
            color: #e67e22;
            font-weight: bold;
        }
        .no-due-date {
            color: #95a5a6;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $student->school->name ?? 'School Name' }}</div>
        <div class="document-title">Homework Assignment Details</div>
        <div class="student-info">
            Student: <strong>{{ $student->name }}</strong> | 
            Class: <strong>{{ $studentDetails->class->name ?? 'N/A' }}</strong> | 
            Section: <strong>{{ $studentDetails->section->name ?? 'N/A' }}</strong> |
            Roll No: <strong>{{ $studentDetails->roll_number ?? 'N/A' }}</strong>
        </div>
        <div class="print-date">Generated on: {{ $printDate }}</div>
    </div>

    <div class="homework-details">
        <div class="detail-row">
            <div class="detail-label">Assignment Date:</div>
            <div class="detail-value">{{ $homework->entry_date->format('F j, Y') }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Subject:</div>
            <div class="detail-value">{{ $homework->subject->name ?? 'General' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Teacher:</div>
            <div class="detail-value">{{ $homework->teacher->name ?? 'Not Assigned' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Class & Section:</div>
            <div class="detail-value">
                {{ $homework->class->name ?? 'N/A' }} - {{ $homework->section->name ?? 'N/A' }}
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Due Date:</div>
            <div class="detail-value">
                @if($homework->due_date)
                    <span class="due-date">{{ $homework->due_date->format('F j, Y') }}</span>
                @else
                    <span class="no-due-date">No due date specified</span>
                @endif
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Status:</div>
            <div class="detail-value">
                <span class="status-badge status-{{ $homework->status }}">
                    {{ ucfirst($homework->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="homework-content">
        <div class="homework-title">{{ $homework->homework_title }}</div>
        <div class="homework-description">{{ $homework->homework_description }}</div>
    </div>

    @if($homework->attachments && count($homework->attachments) > 0)
    <div class="attachments-section">
        <div class="attachments-title">
            📎 Attachments ({{ count($homework->attachments) }})
        </div>
        @foreach($homework->attachments as $attachment)
        <div class="attachment-item">
            <strong>{{ $attachment['name'] ?? 'Attachment' }}</strong>
            @if(isset($attachment['size']))
            <span style="color: #666; font-size: 12px; margin-left: 10px;">
                ({{ number_format($attachment['size'] / 1024, 1) }} KB)
            </span>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <div class="footer">
        This document was generated by the {{ $student->school->name ?? 'School' }} Student Portal.<br>
        Homework ID: {{ $homework->id }} | Student ID: {{ $studentDetails->student_id ?? 'N/A' }}
    </div>
</body>
</html>