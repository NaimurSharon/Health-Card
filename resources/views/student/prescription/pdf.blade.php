<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Prescription - {{ $prescription->id }}</title>
    
    <style>
        /* Font and Base Styles */
        * {
            font-family: 'Noto Sans Bengali', 'Kalpurush', 'Siyam Rupali', 'SolaimanLipi', 'Arial Unicode MS', sans-serif;
        }
        
        body {
            margin: 0;
            padding: 25px;
            color: #333;
            line-height: 1.6;
        }
        
        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .main-title {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .main-title h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        
        .record-info {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 6px;
            margin: 10px 0;
        }
        
        /* Patient Information */
        .patient-info {
            background: linear-gradient(to right, #f8fafc, #f0f9ff);
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .section-title {
            color: #2563eb;
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #2563eb;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            padding: 10px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        
        .info-label {
            color: #64748b;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .info-value {
            color: #334155;
            font-weight: 500;
            font-size: 14px;
            margin-top: 4px;
        }
        
        /* Doctor Information */
        .doctor-info {
            background: #eff6ff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }
        
        .doctor-name {
            font-size: 18px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 5px;
        }
        
        /* Medical Details */
        .medical-section {
            margin-bottom: 20px;
        }
        
        .detail-box {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .detail-box.medication {
            border-left: 4px solid #2563eb;
            background: #eff6ff;
        }
        
        .detail-box.diagnosis {
            border-left: 4px solid #10b981;
            background: #f0fdf4;
        }
        
        .detail-box.symptoms {
            border-left: 4px solid #f59e0b;
            background: #fffbeb;
        }
        
        .detail-label {
            font-weight: 700;
            color: #2563eb;
            font-size: 14px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .detail-content {
            color: #334155;
            font-size: 14px;
            line-height: 1.6;
        }
        
        /* Vital Signs */
        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .vital-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .vital-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .vital-value {
            font-size: 20px;
            font-weight: 700;
            color: #2563eb;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
        }
        
        .footer p {
            margin: 5px 0;
            color: #64748b;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="main-title">
            <h1>প্রেসক্রিপশন / Medical Prescription</h1>
        </div>
        <div class="record-info">
            <strong>Record #{{ $prescription->id }}</strong> | 
            Date: {{ \Carbon\Carbon::parse($prescription->record_date)->format('F d, Y') }} |
            Type: {{ ucfirst($prescription->record_type ?? 'General') }}
        </div>
    </div>

    <!-- Patient Information -->
    <div class="patient-info">
        <h2 class="section-title">রোগীর তথ্য / Patient Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Patient Name</div>
                <div class="info-value">{{ $studentDetails->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Student ID</div>
                <div class="info-value">{{ $studentDetails->student_id ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Class</div>
                <div class="info-value">{{ $studentDetails->class->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Section</div>
                <div class="info-value">{{ $studentDetails->section->name ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Doctor Information -->
    @if($prescription->recordedBy)
    <div class="doctor-info">
        <h3 class="section-title">নির্ধারিত চিকিৎসক / Prescribed By</h3>
        <div class="doctor-name">{{ $prescription->recordedBy->name }}</div>
        <div style="color: #64748b;">{{ $prescription->recordedBy->email }}</div>
    </div>
    @endif

    <!-- Vital Signs -->
    @if($prescription->height || $prescription->weight || $prescription->temperature || $prescription->blood_pressure)
    <div class="medical-section">
        <h3 class="section-title">গুরুত্বপূর্ণ লক্ষণ / Vital Signs</h3>
        <div class="vitals-grid">
            @if($prescription->height)
            <div class="vital-card">
                <div class="vital-label">Height</div>
                <div class="vital-value">{{ $prescription->height }} cm</div>
            </div>
            @endif
            @if($prescription->weight)
            <div class="vital-card">
                <div class="vital-label">Weight</div>
                <div class="vital-value">{{ $prescription->weight }} kg</div>
            </div>
            @endif
            @if($prescription->temperature)
            <div class="vital-card">
                <div class="vital-label">Temperature</div>
                <div class="vital-value">{{ $prescription->temperature }}°F</div>
            </div>
            @endif
            @if($prescription->blood_pressure)
            <div class="vital-card">
                <div class="vital-label">Blood Pressure</div>
                <div class="vital-value">{{ $prescription->blood_pressure }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Medical Details -->
    <div class="medical-section">
        <h3 class="section-title">চিকিৎসা বিবরণ / Medical Details</h3>
        
        @if($prescription->symptoms)
        <div class="detail-box symptoms">
            <div class="detail-label">লক্ষণ / Symptoms</div>
            <div class="detail-content">{{ $prescription->symptoms }}</div>
        </div>
        @endif

        @if($prescription->diagnosis)
        <div class="detail-box diagnosis">
            <div class="detail-label">রোগ নির্ণয় / Diagnosis</div>
            <div class="detail-content">{{ $prescription->diagnosis }}</div>
        </div>
        @endif

        @if($prescription->medication)
        <div class="detail-box medication">
            <div class="detail-label">ঔষধ / Medication</div>
            <div class="detail-content">{{ $prescription->medication }}</div>
        </div>
        @endif

        @if($prescription->doctor_notes)
        <div class="detail-box">
            <div class="detail-label">ডাক্তারের নোট / Doctor's Notes</div>
            <div class="detail-content">{{ $prescription->doctor_notes }}</div>
        </div>
        @endif

        @if($prescription->follow_up_date)
        <div class="detail-box">
            <div class="detail-label">ফলো-আপ তারিখ / Follow-up Date</div>
            <div class="detail-content">
                {{ \Carbon\Carbon::parse($prescription->follow_up_date)->format('F d, Y') }}
            </div>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>This is an official medical prescription</strong></p>
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p style="font-size: 10px; color: #94a3b8; margin-top: 10px;">
            Confidential Document • For medical use only
        </p>
    </div>
</body>
</html>
