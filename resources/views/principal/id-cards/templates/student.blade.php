<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card - {{ $student->user->name }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .id-card {
            width: 85.6mm;
            height: 53.98mm;
            background: white;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* School Header */
        .school-header {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 8px 12px;
            text-align: center;
        }

        .school-name {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .school-motto {
            font-size: 6px;
            opacity: 0.9;
        }

        /* Card Content */
        .card-content {
            padding: 8px 12px;
            display: flex;
            gap: 8px;
        }

        .photo-section {
            flex-shrink: 0;
        }

        .student-photo {
            width: 25mm;
            height: 25mm;
            background: #f0f0f0;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .info-section {
            flex-grow: 1;
        }

        .student-name {
            font-size: 12px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .info-row {
            font-size: 8px;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
        }

        .info-label {
            color: #666;
            width: 65px;
            font-weight: 500;
        }

        .info-value {
            color: #333;
            font-weight: 400;
        }

        .qr-code {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 20mm;
            height: 20mm;
            border: 1px solid #e5e5e5;
            padding: 2px;
            background: white;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
        }

        /* Footer */
        .card-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 4px 12px;
            background: #f9f9f9;
            border-top: 1px solid #eee;
            font-size: 6px;
            color: #666;
            text-align: center;
        }

        /* Signature */
        .signature-section {
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px dashed #ddd;
        }

        .signature-line {
            width: 40mm;
            height: 1px;
            background: #333;
            margin: 2px 0;
        }

        .signature-text {
            font-size: 6px;
            color: #666;
        }

        /* Validation Sticker */
        .validation-sticker {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 15mm;
            height: 15mm;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
        }
    </style>
</head>

<body>
    <div class="id-card">
        <!-- School Header -->
        <div class="school-header">
            <div class="school-name">{{ $school->name }}</div>
            <div class="school-motto">{{ $school->motto ?? 'Excellence in Education' }}</div>
        </div>

        <!-- Card Content -->
        <div class="card-content">
            <div class="photo-section">
                <div class="student-photo">
                    @if($student->user->profile_image_url)
                        <img src="{{ $student->user->profile_image_url }}" alt="Student Photo">
                    @else
                        <span style="font-size: 10px; color: #999;">No Photo</span>
                    @endif
                </div>
            </div>

            <div class="info-section">
                <div class="student-name">{{ $student->user->name }}</div>

                <div class="info-row">
                    <span class="info-label">Student ID:</span>
                    <span class="info-value">{{ $student->student_id }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span class="info-value">{{ $student->class->name ?? 'N/A' }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Section:</span>
                    <span class="info-value">{{ $student->section->name ?? 'N/A' }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Roll No:</span>
                    <span class="info-value">{{ $student->roll_number }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Blood Group:</span>
                    <span class="info-value">{{ $student->blood_group ?? 'N/A' }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Emergency:</span>
                    <span class="info-value">{{ $student->emergency_contact ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- QR Code -->
            <div class="qr-code">
                @if(isset($qrCodePath) && file_exists($qrCodePath))
                    <img src="{{ $qrCodePath }}" alt="QR Code">
                @elseif(isset($qrCode))
                    {!! $qrCode !!}
                @else
                    <div
                        style="display: flex; align-items: center; justify-content: center; height: 100%; color: #999; font-size: 8px;">
                        QR Code
                    </div>
                @endif
            </div>
        </div>

        <!-- Validation Sticker -->
        <div class="validation-sticker">
            <div>VALID<br>{{ now()->format('Y') }}</div>
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="text-align: left;">
                    <div>Issued: {{ now()->format('d/m/Y') }}</div>
                    <div>Expires: {{ now()->addYear()->format('d/m/Y') }}</div>
                </div>

                <div class="signature-section">
                    <div class="signature-line"></div>
                    <div class="signature-text">Authorized Signature</div>
                </div>
            </div>

            <div style="margin-top: 2px; font-size: 5px;">
                ID: {{ $student->student_id }} | {{ $school->phone ?? 'Contact School Office' }}
            </div>
        </div>
    </div>
</body>

</html>