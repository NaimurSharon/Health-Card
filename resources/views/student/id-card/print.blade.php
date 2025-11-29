<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Health Card - {{ $idCard->card_number }}</title>
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
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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
<<<<<<< HEAD
          font-family: "Tiro Bangla", serif!important;
=======
          font-family: "Tiro Bangla", 'Inter'!important;
>>>>>>> c356163 (video call ui setup)
          font-weight: 400;
          font-style: normal;
        }
        
        .tiro-italic {
          font-family: "Tiro Bangla", serif;
          font-weight: 400;
          font-style: italic;
        }
        
<<<<<<< HEAD
=======
        .inter{
            font-family: 'Inter', sans-serif;
        }
        
>>>>>>> c356163 (video call ui setup)
        /* Dynamic ID Card Size from Database */
        .card-container {
            width: {{ $idCard->template->width ?? 85 }}mm;
            height: {{ $idCard->template->height ?? 54 }}mm;
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
                rgba(255, 255, 255, 0.9) 0%, 
                rgba(255, 255, 255, 0.8) 100%);
            z-index: 2;
        }
        
        .card-content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            padding: max(4mm, {{ ($idCard->template->height ?? 54) * 0.03 }}mm) max(5mm, {{ ($idCard->template->width ?? 85) * 0.04 }}mm);
        }
        
        .card-header {
            text-align: center;
            margin-bottom: max(3mm, {{ ($idCard->template->height ?? 54) * 0.02 }}mm);
            padding-bottom: max(2mm, {{ ($idCard->template->height ?? 54) * 0.01 }}mm);
            border-bottom: 1px solid rgba(102, 126, 234, 0.3);
        }
        
        .organization-name {
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
            letter-spacing: 0.5px;
            line-height: 1.1;
            font-size: max(10px, {{ ($idCard->template->width ?? 85) * 0.18 }}px);
        }
        
        .card-type {
            color: #667eea;
            font-weight: 600;
            margin: max(1mm, {{ ($idCard->template->height ?? 54) * 0.005 }}mm) 0 0 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: max(8px, {{ ($idCard->template->width ?? 85) * 0.12 }}px);
        }
        
        .card-body {
            flex: 1;
            display: flex;
            gap: max(4mm, {{ ($idCard->template->width ?? 85) * 0.03 }}mm);
            margin-bottom: max(3mm, {{ ($idCard->template->height ?? 54) * 0.02 }}mm);
        }
        
        .left-section {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: max(3mm, {{ ($idCard->template->height ?? 54) * 0.02 }}mm);
            width: {{ ($idCard->template->width ?? 85) * 0.25 }}mm;
        }
        
        .user-photo {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #667eea;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            object-fit: contain;
            overflow: hidden;
            width: {{ ($idCard->template->width ?? 85) * 0.22 }}mm;
            height: {{ ($idCard->template->height ?? 54) * 0.25 }}mm;
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
            font-size: max(6px, {{ ($idCard->template->width ?? 85) * 0.08 }}px);
        }
        
        .qr-code-container {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 3px;
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: {{ ($idCard->template->width ?? 85) * 0.22 }}mm;
            height: {{ ($idCard->template->width ?? 85) * 0.22 }}mm;
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
            gap: max(1mm, {{ ($idCard->template->height ?? 54) * 0.008 }}mm);
        }
        
        .info-row {
            display: flex;
            align-items: center;
            padding: max(0.5mm, {{ ($idCard->template->height ?? 54) * 0.005 }}mm) 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .info-label {
            font-weight: 700;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            min-width: {{ ($idCard->template->width ?? 85) * 0.18 }}mm;
            font-size: max(6px, {{ ($idCard->template->width ?? 85) * 0.09 }}px);
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
            font-size: max(7px, {{ ($idCard->template->width ?? 85) * 0.1 }}px);
        }
        
        .card-number {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 2px;
            font-weight: 700;
            padding: max(1px, {{ ($idCard->template->width ?? 85) * 0.005 }}mm) max(2px, {{ ($idCard->template->width ?? 85) * 0.01 }}mm);
            font-size: max(6px, {{ ($idCard->template->width ?? 85) * 0.08 }}px);
        }
        
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: max(2mm, {{ ($idCard->template->height ?? 54) * 0.015 }}mm);
        }
        
        .barcode-section {
            flex: 1;
            text-align: center;
        }
        
        .barcode {
            max-width: {{ ($idCard->template->width ?? 85) * 0.5 }}mm;
            margin: 0 auto;
        }
        
        .barcode img {
            width: 100%;
            height: max(12px, {{ ($idCard->template->height ?? 54) * 0.15 }}mm);
            object-fit: contain;
            border-radius: 1px;
            padding: 1px;
        }
        
        .code-label {
            color: #6c757d;
            margin-top: 1px;
            font-weight: 600;
            font-size: max(5px, {{ ($idCard->template->width ?? 85) * 0.07 }}px);
        }
        
        .signature-section {
            text-align: center;
            flex-shrink: 0;
        }
        
        .signature-line {
            border-top: 1px solid #2c3e50;
            margin: 1px auto;
            width: {{ ($idCard->template->width ?? 85) * 0.3 }}mm;
        }
        
        .signature-text {
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: max(5px, {{ ($idCard->template->width ?? 85) * 0.07 }}px);
        }
        
        .expiry-badge {
            position: absolute;
            top: max(3mm, {{ ($idCard->template->height ?? 54) * 0.02 }}mm);
            right: max(3mm, {{ ($idCard->template->width ?? 85) * 0.02 }}mm);
            background: {{ $idCard->is_expired ? '#e74c3c' : '#27ae60' }};
            color: white;
            border-radius: 2px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            z-index: 4;
            padding: max(1px, {{ ($idCard->template->width ?? 85) * 0.005 }}mm) max(2px, {{ ($idCard->template->width ?? 85) * 0.01 }}mm);
            font-size: max(5px, {{ ($idCard->template->width ?? 85) * 0.07 }}px);
        }
        
        .holder-name {
            font-weight: 700 !important;
            color: #2c3e50 !important;
            font-size: max(8px, {{ ($idCard->template->width ?? 85) * 0.12 }}px) !important;
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Print ID Card
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            ❌ Close
        </button>
        <div style="display: inline-block; margin-left: 10px; font-size: 12px; color: #666;">
            Size: {{ $idCard->template->width ?? 85 }}mm × {{ $idCard->template->height ?? 54 }}mm
        </div>
    </div>

    <div class="print-page">
        <div class="card-container">
            <!-- Expiry Badge -->
<<<<<<< HEAD
            <div class="expiry-badge">
=======
            <div class="expiry-badge {{ detectLanguageClass($idCard->is_expired ? 'EXPIRED' : 'VALID') }}">
>>>>>>> c356163 (video call ui setup)
                {{ $idCard->is_expired ? 'EXPIRED' : 'VALID' }}
            </div>
            
            <!-- Background Image -->
            @if($idCard->template && $idCard->template->background_image)
            <img src="{{ asset('public/storage/' . $idCard->template->background_image) }}" 
                 alt="Card Background" 
                 class="card-background">
            @endif
            
            <!-- Overlay for better readability -->
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
<<<<<<< HEAD
                    <div class="organization-name tiro">{{ $organizationName }}</div>
                    <div class="card-type">
=======
                    <div class="organization-name {{ detectLanguageClass($organizationName) }}">{{ $organizationName }}</div>
                    <div class="card-type {{ detectLanguageClass(ucfirst($idCard->type) . ' ID CARD') }}">
>>>>>>> c356163 (video call ui setup)
                        {{ ucfirst($idCard->type) }} ID CARD
                    </div>
                </div>
                
                <!-- Body -->
                <div class="card-body">
                    <div class="left-section">
                        @if($idCard->student && $idCard->student->user && $idCard->student->user->profile_image)
                            <div class="user-photo">
                                <img src="{{ asset('public/storage/' . $idCard->student->user->profile_image) }}" 
                                     alt="{{ $idCard->student->user->name }}">
                            </div>
                        @elseif($idCard->user && $idCard->user->profile_image)
                            <div class="user-photo">
                                <img src="{{ asset('public/storage/' . $idCard->user->profile_image) }}" 
                                     alt="{{ $idCard->user->name }}">
                            </div>
                        @else
                            <div class="user-photo">
<<<<<<< HEAD
                                <div class="photo-placeholder">
=======
                                <div class="photo-placeholder {{ detectLanguageClass('PHOTO') }}">
>>>>>>> c356163 (video call ui setup)
                                    <div>📷</div>
                                    <div>PHOTO</div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- QR Code -->
                        @if($idCard->qr_code && file_exists(public_path('storage/' . $idCard->qr_code)))
                            <div class="qr-code-container">
                                <img src="{{ asset('public/storage/' . $idCard->qr_code) }}" alt="QR Code">
                            </div>
                        @else
                            <div class="qr-code-container" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
<<<<<<< HEAD
                                <div style="text-align: center; color: #6c757d; font-size: max(6px, {{ ($idCard->template->width ?? 85) * 0.08 }}px);">
=======
                                <div style="text-align: center; color: #6c757d; font-size: max(6px, {{ ($idCard->template->width ?? 85) * 0.08 }}px);" class="{{ detectLanguageClass('QR CODE') }}">
>>>>>>> c356163 (video call ui setup)
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
<<<<<<< HEAD
                                <span class="info-label">NAME:</span>
                                <span class="info-value holder-name">{{ $idCard->card_holder_name }}</span>
=======
                                <span class="info-label {{ detectLanguageClass('NAME:') }}">NAME:</span>
                                <span class="info-value holder-name {{ detectLanguageClass($idCard->card_holder_name) }}">{{ $idCard->card_holder_name }}</span>
>>>>>>> c356163 (video call ui setup)
                            </div>
                            
                            @if($idCard->student)
                            <div class="info-row">
<<<<<<< HEAD
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
=======
                                <span class="info-label {{ detectLanguageClass('STUDENT ID:') }}">STUDENT ID:</span>
                                <span class="info-value {{ detectLanguageClass($idCard->student->student_id ?? 'N/A') }}">{{ $idCard->student->student_id ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label {{ detectLanguageClass('CLASS:') }}">CLASS:</span>
                                <span class="info-value {{ detectLanguageClass($idCard->student->class->name ?? 'N/A') }}">{{ $idCard->student->class->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label {{ detectLanguageClass('ROLL NO:') }}">ROLL NO:</span>
                                <span class="info-value {{ detectLanguageClass($idCard->student->roll_number ?? 'N/A') }}">{{ $idCard->student->roll_number ?? 'N/A' }}</span>
>>>>>>> c356163 (video call ui setup)
                            </div>
                            @endif
                            
                            @if($idCard->user)
                            <div class="info-row">
<<<<<<< HEAD
                                <span class="info-label">POSITION:</span>
                                <span class="info-value">{{ ucfirst($idCard->user->role) }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">DEPT:</span>
                                <span class="info-value">{{ $idCard->user->department ?? ($idCard->user->specialization ?? 'N/A') }}</span>
                            </div>
                            @endif

                            <!-- Medical Information -->
                            @if($idCard->student)
                            <div class="info-row">
                                <span class="info-label">BLOOD GROUP:</span>
                                <span class="info-value" style="color: #e74c3c; font-weight: 700;">
=======
                                <span class="info-label {{ detectLanguageClass('POSITION:') }}">POSITION:</span>
                                <span class="info-value {{ detectLanguageClass(ucfirst($idCard->user->role)) }}">{{ ucfirst($idCard->user->role) }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label {{ detectLanguageClass('DEPT:') }}">DEPT:</span>
                                <span class="info-value {{ detectLanguageClass($idCard->user->department ?? ($idCard->user->specialization ?? 'N/A')) }}">{{ $idCard->user->department ?? ($idCard->user->specialization ?? 'N/A') }}</span>
                            </div>
                            @endif
    
                            <!-- Medical Information -->
                            @if($idCard->student)
                            <div class="info-row">
                                <span class="info-label {{ detectLanguageClass('BLOOD GROUP:') }}">BLOOD GROUP:</span>
                                <span class="info-value {{ detectLanguageClass($idCard->student->blood_group ?? 'N/A') }}" style="color: #e74c3c; font-weight: 700;">
>>>>>>> c356163 (video call ui setup)
                                    {{ $idCard->student->blood_group ?? 'N/A' }}
                                </span>
                            </div>
                            
                            @if($idCard->student->allergies && $idCard->student->allergies !== 'None')
                            <div class="info-row">
<<<<<<< HEAD
                                <span class="info-label">ALLERGIES:</span>
                                <span class="info-value" style="color: #e74c3c; font-weight: 600;">
=======
                                <span class="info-label {{ detectLanguageClass('ALLERGIES:') }}">ALLERGIES:</span>
                                <span class="info-value {{ detectLanguageClass($idCard->student->allergies) }}" style="color: #e74c3c; font-weight: 600;">
>>>>>>> c356163 (video call ui setup)
                                    {{ $idCard->student->allergies }}
                                </span>
                            </div>
                            @endif
                            
                            <div class="info-row">
<<<<<<< HEAD
                                <span class="info-label">EMERGENCY:</span>
                                <span class="info-value" style="color: #e74c3c; font-weight: 700;">
=======
                                <span class="info-label {{ detectLanguageClass('EMERGENCY:') }}">EMERGENCY:</span>
                                <span class="info-value {{ detectLanguageClass($idCard->student->emergency_contact ?? 'N/A') }}" style="color: #e74c3c; font-weight: 700;">
>>>>>>> c356163 (video call ui setup)
                                    {{ $idCard->student->emergency_contact ?? 'N/A' }}
                                </span>
                            </div>
                            @endif
                            
                            <div class="info-row">
<<<<<<< HEAD
                                <span class="info-label">CARD NO:</span>
                                <span class="info-value">
                                    <span class="card-number">{{ $idCard->card_number }}</span>
=======
                                <span class="info-label {{ detectLanguageClass('CARD NO:') }}">CARD NO:</span>
                                <span class="info-value">
                                    <span class="card-number {{ detectLanguageClass($idCard->card_number) }}">{{ $idCard->card_number }}</span>
>>>>>>> c356163 (video call ui setup)
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="card-footer">
<<<<<<< HEAD
                    <!-- Barcode -->
                    <!--<div class="barcode-section">-->
                    <!--    @if($idCard->barcode && file_exists(public_path('storage/' . $idCard->barcode)))-->
                    <!--        <div class="barcode">-->
                    <!--            <img src="{{ asset('storage/' . $idCard->barcode) }}" alt="Barcode">-->
                    <!--            <div class="code-label">{{ $idCard->card_number }}</div>-->
                    <!--        </div>-->
                    <!--    @else-->
                    <!--        <div class="barcode" style="background: #f8f9fa; padding: 2px; border-radius: 2px;">-->
                    <!--            <div style="text-align: center; color: #6c757d; font-size: max(6px, {{ ($idCard->template->width ?? 85) * 0.08 }}px);">-->
                    <!--                <div style="letter-spacing: 1px; font-family: monospace;">{{ $idCard->card_number }}</div>-->
                    <!--                <div class="code-label">BARCODE</div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    @endif-->
                    <!--</div>-->
                    
                    <!-- Signature -->
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <div class="signature-text">AUTHORIZED SIGNATURE</div>
=======
                    <!-- Signature -->
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <div class="signature-text {{ detectLanguageClass('AUTHORIZED SIGNATURE') }}">AUTHORIZED SIGNATURE</div>
>>>>>>> c356163 (video call ui setup)
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

        // Close window after print
        window.onafterprint = function() {
            // Optional: auto-close after printing
            // window.close();
        };
    </script>
</body>
</html>