<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Card - {{ $healthCard->card_number }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', 'logo/amardesh-shadhinotar-kotha-bole.webp')) }}" type="image/webp">
    
    <!-- Fonts -->
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
            .health-card-container { 
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
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 8px;
        }
        
        .info-row {
            display: flex;
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
        }
        
        .hidden {
            display: none !important;
        }

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