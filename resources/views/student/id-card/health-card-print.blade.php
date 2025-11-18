<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Health Card - {{ $healthCard->card_number }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', 'logo/amardesh-shadhinotar-kotha-bole.webp')) }}" type="image/webp">
    
        <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">
    
    <style>
        @media print {
            body { 
                margin: 0; 
                padding: 0; 
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { 
                display: none !important; 
            }
            .card-container { 
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                page-break-inside: avoid;
            }
            .print-page {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100vw;
                height: 100vh;
            }
        }
        
        @media screen {
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                min-height: 100vh;
                padding: 20px;
                margin: 0;
            }
            .print-page {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
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
          font-family: "Tiro Bangla", serif!important;
          font-weight: 400;
          font-style: normal;
        }
        
        .tiro-italic {
          font-family: "Tiro Bangla", serif;
          font-weight: 400;
          font-style: italic;
        }
        
        .no-print {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            z-index: 1000;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 5px;
            font-size: 14px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .card-container {
            width: 85mm;
            height: 54mm;
            position: relative;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            background: linear-gradient(135deg, 
                rgba(255, 255, 255, 0.95) 0%, 
                rgba(255, 255, 255, 0.9) 100%);
            z-index: 2;
        }
        
        .card-content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 4mm 5mm;
            box-sizing: border-box;
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 3mm;
            padding-bottom: 2mm;
            border-bottom: 2px solid #e74c3c;
        }
        
        .organization-name {
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .card-type {
            color: #e74c3c;
            font-weight: 700;
            margin: 1mm 0 0 0;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        
        .card-body {
            flex: 1;
            display: flex;
            gap: 4mm;
            margin-bottom: 3mm;
        }
        
        .left-section {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3mm;
            width: 25mm;
        }
        
        .user-photo {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 2px solid #e74c3c;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            width: 22mm;
            height: 25mm;
        }
        
        .user-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            text-align: center;
            color: #6c757d;
            padding: 2mm;
            font-size: 8px;
        }
        
        .qr-code-container {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 3px;
            padding: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22mm;
            height: 22mm;
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
            gap: 1mm;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            padding: 0.5mm 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .info-label {
            font-weight: 700;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            min-width: 18mm;
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
        
        .card-number {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border-radius: 2px;
            font-weight: 700;
            padding: 1px 2px;
            font-size: 7px;
        }
        
        .medical-alert {
            background: #e74c3c;
            color: white;
            border-radius: 2px;
            font-weight: 700;
            padding: 1px 3px;
            font-size: 6px;
            text-transform: uppercase;
        }
        
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 2mm;
        }
        
        .emergency-section {
            flex: 1;
        }
        
        .emergency-info {
            background: #e74c3c;
            color: white;
            border-radius: 3px;
            padding: 2px 4px;
            font-size: 7px;
            font-weight: 600;
        }
        
        .signature-section {
            text-align: center;
            flex-shrink: 0;
        }
        
        .signature-line {
            border-top: 1px solid #2c3e50;
            margin: 1px auto;
            width: 25mm;
        }
        
        .signature-text {
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 6px;
        }
        
        .expiry-badge {
            position: absolute;
            top: 3mm;
            right: 3mm;
            background: {{ $healthCard->is_expired ? '#e74c3c' : '#27ae60' }};
            color: white;
            border-radius: 2px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            z-index: 4;
            padding: 1px 2px;
            font-size: 6px;
        }
        
        .holder-name {
            font-weight: 700 !important;
            color: #2c3e50 !important;
            font-size: 9px !important;
        }
        
        .blood-group {
            color: #e74c3c;
            font-weight: 700;
        }
        
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Print Health Card
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            ❌ Close
        </button>
    </div>

    <div class="print-page">
        <div class="card-container">
            <!-- Expiry Badge -->
            <div class="expiry-badge">
                {{ $healthCard->is_expired ? 'EXPIRED' : 'VALID' }}
            </div>
            
            <!-- Background Overlay -->
            <div class="card-overlay"></div>
            
            <!-- Card Content -->
            <div class="card-content">
                <!-- Header -->
                <div class="card-header">
                    <div class="organization-name tiro">{{ Auth::user()->school->name ?? 'SCHOOL NAME' }}</div>
                    <div class="card-type">HEALTH CARD</div>
                </div>
                
                <!-- Body -->
                <div class="card-body">
                    <div class="left-section">
                        <!-- Student Photo -->
                        @if(Auth::user()->profile_image)
                            <div class="user-photo">
                                <img src="{{ asset('public/storage/' . Auth::user()->profile_image) }}" 
                                     alt="{{ Auth::user()->name }}">
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
                        @if($healthCard->qr_code && file_exists(public_path('storage/' . $healthCard->qr_code)))
                            <div class="qr-code-container">
                                <img src="{{ asset('public/storage/' . $healthCard->qr_code) }}" alt="QR Code">
                            </div>
                        @else
                            <div class="qr-code-container" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <div style="text-align: center; color: #6c757d; font-size: 7px;">
                                    <div style="margin-bottom: 1px;">📱</div>
                                    QR CODE
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Information Section -->
                    <div class="info-section">
                        <div class="info-grid">
                            <div class="info-row">
                                <span class="info-label">NAME:</span>
                                <span class="info-value holder-name">{{ Auth::user()->name }}</span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">STUDENT ID:</span>
                                <span class="info-value">{{ Auth::user()->student->student_id ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">CLASS:</span>
                                <span class="info-value">{{ Auth::user()->student->class->name ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="info-row">
                                <span class="info-label">BLOOD GROUP:</span>
                                <span class="info-value blood-group">
                                    {{ Auth::user()->student->blood_group ?? 'N/A' }}
                                </span>
                            </div>
                            
                            @if(Auth::user()->student->allergies && Auth::user()->student->allergies !== 'None')
                            <div class="info-row">
                                <span class="info-label">ALLERGIES:</span>
                                <span class="info-value medical-alert">
                                    {{ Auth::user()->student->allergies }}
                                </span>
                            </div>
                            @endif
                            
                            <div class="info-row">
                                <span class="info-label">CARD NO:</span>
                                <span class="info-value">
                                    <span class="card-number">{{ $healthCard->card_number }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="card-footer">
                    <!-- Emergency Information -->
                    <div class="emergency-section">
                        <div class="emergency-info">
                            <strong>EMERGENCY:</strong> {{ $healthCard->emergency_instructions ?? Auth::user()->student->emergency_contact ?? 'CONTACT SCHOOL' }}
                        </div>
                    </div>
                    
                    <!-- Signature -->
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <div class="signature-text">MEDICAL OFFICER</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto-print after a short delay
            setTimeout(() => {
                window.print();
            }, 500);
        };

        window.onafterprint = function() {
            // Optional: auto-close after printing
            // window.close();
        };
    </script>
</body>
</html>