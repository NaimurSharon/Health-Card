<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Print ID Cards</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .card-container { break-inside: avoid; page-break-inside: avoid; }
            .cards-grid { break-inside: avoid; }
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }
        
        .print-actions {
            padding: 20px;
            background: white;
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        
        .card-container {
            width: 100%;
            height: 180px;
            position: relative;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            background: white;
        }
        
        .card-content {
            padding: 15px;
            height: 100%;
            display: flex;
        }
        
        .photo-section {
            width: 80px;
            margin-right: 15px;
        }
        
        .user-photo {
            width: 70px;
            height: 85px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #6c757d;
        }
        
        .info-section {
            flex: 1;
            font-size: 12px;
        }
        
        .info-row {
            margin-bottom: 4px;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
        }
        
        .card-number {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .card-type {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #3498db;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="no-print print-actions">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print All
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    <div class="cards-grid">
        @foreach($idCards as $idCard)
        <div class="card-container">
            <div class="card-type">{{ ucfirst($idCard->type) }}</div>
            
            <div class="card-content">
                <!-- Photo Section -->
                <div class="photo-section">
                    <div class="user-photo">
                        @if($idCard->card_holder_photo)
                            <img src="{{ asset('storage/' . $idCard->card_holder_photo) }}" 
                                 alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            No Photo
                        @endif
                    </div>
                </div>
                
                <!-- Information Section -->
                <div class="info-section">
                    <div class="card-number">{{ $idCard->card_number }}</div>
                    
                    <div class="info-row">
                        <span class="info-label">Name:</span> {{ $idCard->card_holder_name }}
                    </div>
                    
                    @if($idCard->student)
                    <div class="info-row">
                        <span class="info-label">Student ID:</span> {{ $idCard->student->student_id ?? 'N/A' }}
                    </div>
                    <div class="info-row">
                        <span class="info-label">Grade:</span> {{ $idCard->student->grade ?? 'N/A' }}
                    </div>
                    @endif
                    
                    @if($idCard->user)
                    <div class="info-row">
                        <span class="info-label">Role:</span> {{ ucfirst($idCard->user->role) }}
                    </div>
                    @endif
                    
                    <div class="info-row">
                        <span class="info-label">Issue:</span> {{ $idCard->issue_date->format('M d, Y') }}
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Expiry:</span> {{ $idCard->expiry_date->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <script>
        window.onload = function() {
            // Auto-print if needed
            // window.print();
        };
    </script>
</body>
</html>