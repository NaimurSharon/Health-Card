<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD

=======
>>>>>>> c356163 (video call ui setup)
    <title>Health Card - {{ $healthCard->card_number }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', 'logo/amardesh-shadhinotar-kotha-bole.webp')) }}" type="image/webp">
    
<<<<<<< HEAD
        <!-- Inter Font -->
=======
    <!-- Fonts -->
>>>>>>> c356163 (video call ui setup)
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
<<<<<<< HEAD
            .card-container { 
                margin: 0 !important;
=======
            .health-card-container { 
                margin: 0 !important;
                
>>>>>>> c356163 (video call ui setup)
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
        
<<<<<<< HEAD
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
=======
        .health-card-container {
            width: 400px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Header Styles */
        .health-card-header {
            text-align: center;
            padding: 24px;
        }
        
        .health-card-title {
            font-size: 24px;
            font-weight: 800;
            color: #16a34a;
            margin-bottom: 8px;
            line-height: 1.2;
        }
        
        .health-card-subtitle {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
        }
        
        .mayor-name {
            font-size: 20px;
            font-weight: 800;
            color: #dc2626;
            margin-bottom: 4px;
        }
        
        .mayor-title {
            font-size: 12px;
            color: #374151;
            margin-bottom: 16px;
        }
        
        .school-name {
            font-size: 16px;
            font-weight: 700;
            color: #dc2626;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 12px;
            margin-bottom: 16px;
            line-height: 1.3;
        }
        
        .student-id-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #374151;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        /* Student Info Section */
        .student-info-section {
            padding: 0 24px 24px;
        }
        
        .student-info-container {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 20px;
            background: white;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .student-info-title {
            font-size: 18px;
            font-weight: 800;
            color: #1f2937;
            text-align: center;
            margin-bottom: 16px;
>>>>>>> c356163 (video call ui setup)
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
<<<<<<< HEAD
            gap: 1mm;
=======
            gap: 8px;
>>>>>>> c356163 (video call ui setup)
        }
        
        .info-row {
            display: flex;
<<<<<<< HEAD
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
=======
            align-items: flex-start;
        }
        
        .info-label {
            width: 40%;
            color: #6b7280;
            font-size: 12px;
        }
        
        .info-value {
            width: 60%;
            font-weight: 600;
            color: #111827;
            font-size: 12px;
            margin-left: 8px;
        }
        
        .blood-group-value {
            color: #dc2626;
            font-weight: 700;
        }
        
        .expiry-section {
            text-align: center;
            padding: 16px;
            background: #f8f9fa;
            border-top: 1px solid #e5e7eb;
        }
        
        .expiry-text {
            font-size: 12px;
            color: #6b7280;
        }
        
        .expiry-date {
            font-weight: 600;
            color: #374151;
>>>>>>> c356163 (video call ui setup)
        }
        
        .hidden {
            display: none !important;
        }
<<<<<<< HEAD
=======

        /* Print optimizations */
        @media print {
            .health-card-container {
                width: 100% !important;
                max-width: 400px !important;
                border-radius: 8px;
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
            
            .health-card-header {
                padding: 20px !important;
            }
            
            .student-info-section {
                padding: 0 20px 20px !important;
            }
            
            .health-card-title {
                font-size: 22px !important;
            }
            
            .mayor-name {
                font-size: 18px !important;
            }
            
            .school-name {
                font-size: 15px !important;
            }
        }
>>>>>>> c356163 (video call ui setup)
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
<<<<<<< HEAD
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
=======
        <div class="health-card-container">
            <!-- Header Section -->
            <div class="health-card-header">
                <h1 class="health-card-title tiro">
                    স্কুল শিক্ষার্থীর স্বাস্থ্য কার্ড
                </h1>
                <p class="health-card-subtitle inter">
                    School's Student Health Card
                </p>
                
                <h2 class="mayor-name tiro">
                    ডা. শাহাদাত হোসেন
                </h2>
                <p class="mayor-title tiro">
                    মেয়র, চট্টগ্রাম সিটি কর্পোরেশন
                </p>
                
                <h3 class="school-name tiro">
                    {{ Auth::user()->school->name ?? 'গুল এজার বেগম সিটি কর্পোরেশন মুসলিম বালিকা উচ্চ বিদ্যালয়' }}
                </h3>
                
                <div class="student-id-section inter">
                    <span class="tiro">ID NO :</span>
                    <span class="font-bold">0233366831</span>
                </div>
            </div>
            
            <!-- Student Information Section -->
            <div class="student-info-section">
                <div class="student-info-container">
                    <h4 class="student-info-title tiro">
                        শিক্ষার্থীর পরিচয়
                    </h4>
                    
                    <div class="info-grid">
                        <!-- Student Name -->
                        <div class="info-row">
                            <span class="info-label tiro">শিক্ষার্থীর নাম</span>
                            <span class="info-value {{ detectLanguageClass(Auth::user()->name) }}">: {{ Auth::user()->name }}</span>
                        </div>
                        
                        <!-- Date of Birth -->
                        <div class="info-row">
                            <span class="info-label tiro">জন্ম তারিখ</span>
                            <span class="info-value inter">: {{ Auth::user()->student->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->student->date_of_birth)->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        
                        <!-- Blood Group -->
                        <div class="info-row">
                            <span class="info-label tiro">রক্তের গ্রুপ</span>
                            <span class="info-value blood-group-value inter">: {{ Auth::user()->student->blood_group ?? 'N/A' }}</span>
                        </div>
                        
                        <!-- Father's Name -->
                        <div class="info-row">
                            <span class="info-label tiro">পিতার নাম</span>
                            <span class="info-value {{ detectLanguageClass(Auth::user()->student->father_name ?? 'N/A') }}">: {{ Auth::user()->student->father_name ?? 'N/A' }}</span>
                        </div>
                        
                        <!-- Mother's Name -->
                        <div class="info-row">
                            <span class="info-label tiro">মাতার নাম</span>
                            <span class="info-value {{ detectLanguageClass(Auth::user()->student->mother_name ?? 'N/A') }}">: {{ Auth::user()->student->mother_name ?? 'N/A' }}</span>
                        </div>
                        
                        <!-- Mobile Number -->
                        <div class="info-row">
                            <span class="info-label tiro">মোবাইল নং</span>
                            <span class="info-value inter">: {{ Auth::user()->student->emergency_contact ?? Auth::user()->phone ?? 'N/A' }}</span>
                        </div>
                        
                        <!-- Class -->
                        <div class="info-row">
                            <span class="info-label tiro">শ্রেণি</span>
                            <span class="info-value {{ detectLanguageClass(Auth::user()->student->class->name ?? 'N/A') }}">: {{ Auth::user()->student->class->name ?? 'N/A' }}</span>
                        </div>
                        
                        <!-- Section -->
                        <div class="info-row">
                            <span class="info-label tiro">শাখা</span>
                            <span class="info-value {{ detectLanguageClass(Auth::user()->student->section->name ?? 'N/A') }}">: {{ Auth::user()->student->section->name ?? 'N/A' }}</span>
                        </div>
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

        window.onafterprint = function() {
            // Optional: auto-close after printing
            // window.close();
        };
    </script>
</body>
</html>