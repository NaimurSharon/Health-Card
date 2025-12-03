<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ID Card - {{ $idCard->card_number }}</title>
    
    <style>
        /* Font and Base Styles */
        * {
            font-family: 'Noto Sans Bengali', 'Kalpurush', 'Siyam Rupali', 'SolaimanLipi', 'Arial Unicode MS', sans-serif;
        }
        
        body {
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .card-wrapper {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .card-container {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            border: 1px solid #e0e0e0;
            margin: 0 auto;
        }
        
        .card-background {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            object-fit: cover;
        }
        
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.4) 100%);
            z-index: 2;
        }
        
        .card-content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            padding: 3mm 4mm;
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 2mm;
            padding-bottom: 1mm;
            border-bottom: 1px solid rgba(102, 126, 234, 0.3);
        }
        
        .organization-name {
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
            letter-spacing: 0.5px;
            line-height: 1.1;
            font-size: 14px;
        }
        
        .card-type {
            color: #667eea;
            font-weight: 600;
            margin: 0.5mm 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 10px;
        }
        
        .card-body {
            flex: 1;
            display: flex;
            gap: 3mm;
            margin-bottom: 2mm;
        }
        
        .left-section {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2mm;
            width: 22mm;
        }
        
        .user-photo {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #667eea;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            width: 20mm;
            height: 14mm;
        }
        
        .user-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            text-align: center;
            color: #6c757d;
            padding: 1mm;
            font-size: 6px;
        }
        
        .qr-code-container {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 3px;
            padding: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20mm;
            height: 20mm;
        }
        
        .qr-code-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .info-section {
            flex: 1;
            min-width: 0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.5mm;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            padding: 0.3mm 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .info-label {
            font-weight: 700;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            min-width: 16mm;
            font-size: 7px;
        }
        
        .info-value {
            font-weight: 600;
            color: #34495e;
            flex: 1;
            line-height: 1.1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 8px;
        }
        
        .holder-name {
            font-weight: 700 !important;
            color: #2c3e50 !important;
            font-size: 10px !important;
        }
        
        .card-number {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 2px;
            font-weight: 700;
            padding: 0.5mm 1mm;
            font-size: 6px;
        }
        
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 1mm;
        }
        
        .signature-section {
            text-align: center;
            flex-shrink: 0;
        }
        
        .signature-line {
            border-top: 1px solid #2c3e50;
            margin: 0.5px auto;
            width: 25mm;
        }
        
        .signature-text {
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 5px;
        }
        
        .expiry-badge {
            position: absolute;
            top: 2mm;
            right: 2mm;
            background: {{ $idCard->is_expired ? '#e74c3c' : '#27ae60' }};
            color: white;
            border-radius: 2px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            z-index: 4;
            padding: 0.5mm 1mm;
            font-size: 5px;
        }
        
        .blood-group {
            color: #e74c3c !important;
            font-weight: 700 !important;
        }
        
        .emergency-contact {
            color: #e74c3c !important;
            font-weight: 700 !important;
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .page-title h1 {
            color: #2c3e50;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .page-title p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }
        
        .card-info {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .card-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .card-info-row:last-child {
            border-bottom: none;
        }
        
        .card-info-label {
            font-weight: 600;
            color: #64748b;
        }
        
        .card-info-value {
            font-weight: 500;
            color: #334155;
        }
    </style>
</head>
<body>
    <div class="card-wrapper">
        <!-- Page Title -->
        <div class="page-title">
            <h1>আইডি কার্ড / ID Card</h1>
            <p>{{ ucfirst($idCard->type) }} Identification Card</p>
        </div>

        <!-- ID Card -->
        <div class="card-container">
            <!-- Expiry Badge -->
            <div class="expiry-badge">
                {{ $idCard->is_expired ? 'EXPIRED' : 'VALID' }}
            </div>
            
            <!-- Background Image -->
            @if($idCard->template && $idCard->template->background_image)
            <img src="{{ public_path('storage/' . $idCard->template->background_image) }}" 
                 alt="Card Background" 
                 class="card-background">
            @endif
            
            <!-- Overlay -->
            <div class="card-overlay"></div>
            
            <!-- Card Content -->
            <div class="card-content">
                <!-- Header -->
                <div class="card-header">
                    @php
                        $organizationName = config('app.name', 'Organization Name');
                        
                        if ($idCard->student && $idCard->student->user && $idCard->student->user->school) {
                            $organizationName = $idCard->student->user->school->name;
                        }
                        elseif ($idCard->user && $idCard->user->school) {
                            $organizationName = $idCard->user->school->name;
                        }
                    @endphp
                    <div class="organization-name">{{ $organizationName }}</div>
                    <div class="card-type">{{ ucfirst($idCard->type) }} ID CARD</div>
                </div>
                
                <!-- Body -->
                <div class="card-body">
                    <div class="left-section">
                        @if($idCard->student && $idCard->student->user && $idCard->student->user->profile_image)
                            <div class="user-photo">
                                <img src="{{ public_path('storage/' . $idCard->student->user->profile_image) }}" 
                                     alt="{{ $idCard->student->user->name }}">
                            </div>
                        @elseif($idCard->user && $idCard->user->profile_image)
                            <div class="user-photo">
                                <img src="{{ public_path('storage/' . $idCard->user->profile_image) }}" 
                                     alt="{{ $idCard->user->name }}">
                            </div>
                        @else
                            <div class="user-photo">
                                <div class="photo-placeholder">
                                    <div>📷</div>
                                    <div>PHOTO</div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- QR Code -->
                        @if($idCard->qr_code && file_exists(public_path('storage/' . $idCard->qr_code)))
                            <div class="qr-code-container">
                                <img src="{{ public_path('storage/' . $idCard->qr_code) }}" alt="QR Code">
                            </div>
                        @else
                            <div class="qr-code-container" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <div style="text-align: center; color: #6c757d; font-size: 6px;">
                                    <div style="margin-bottom: 1px;">📱</div>
                                    QR CODE
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Right Section - Information -->
                    <div class="info-section">
                        <div class="info-grid">
                            <div class="info-row">
                                <span class="info-label">NAME:</span>
                                <span class="info-value holder-name">{{ $idCard->card_holder_name }}</span>
                            </div>
                            
                            @if($idCard->student)
                            <div class="info-row">
                                <span class="info-label">STUDENT ID:</span>
                                <span class="info-value">{{ $idCard->student->student_id ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">CLASS:</span>
                                <span class="info-value">{{ $idCard->student->class->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">ROLL NO:</span>
                                <span class="info-value">{{ $idCard->student->roll_number ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">BLOOD GROUP:</span>
                                <span class="info-value blood-group">{{ $idCard->student->blood_group ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">EMERGENCY:</span>
                                <span class="info-value emergency-contact">{{ $idCard->student->emergency_contact ?? 'N/A' }}</span>
                            </div>
                            @endif
                            
                            @if($idCard->user)
                            <div class="info-row">
                                <span class="info-label">POSITION:</span>
                                <span class="info-value">{{ ucfirst($idCard->user->role) }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">DEPT:</span>
                                <span class="info-value">{{ $idCard->user->department ?? ($idCard->user->specialization ?? 'N/A') }}</span>
                            </div>
                            @endif
                            
                            <div class="info-row">
                                <span class="info-label">CARD NO:</span>
                                <span class="info-value">
                                    <span class="card-number">{{ $idCard->card_number }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="card-footer">
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <div class="signature-text">AUTHORIZED SIGNATURE</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Information -->
        <div class="card-info">
            <div class="card-info-row">
                <span class="card-info-label">Card Number:</span>
                <span class="card-info-value">{{ $idCard->card_number }}</span>
            </div>
            <div class="card-info-row">
                <span class="card-info-label">Issue Date:</span>
                <span class="card-info-value">{{ $idCard->issue_date->format('F d, Y') }}</span>
            </div>
            <div class="card-info-row">
                <span class="card-info-label">Expiry Date:</span>
                <span class="card-info-value">{{ $idCard->expiry_date->format('F d, Y') }}</span>
            </div>
            <div class="card-info-row">
                <span class="card-info-label">Status:</span>
                <span class="card-info-value" style="color: {{ $idCard->is_expired ? '#e74c3c' : '#27ae60' }}; font-weight: 700;">
                    {{ $idCard->is_expired ? 'EXPIRED' : 'VALID' }}
                </span>
            </div>
        </div>
    </div>
</body>
</html>
