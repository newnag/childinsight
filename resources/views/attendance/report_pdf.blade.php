<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานการเข้าเรียน - {{ \Carbon\Carbon::parse($month)->locale('th')->isoFormat('MMMM YYYY') }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Sarabun', sans-serif; /* Assuming Sarabun or similar Thai font is desired */
            background-color: #fff;
            padding: 20px;
        }
        @media print {
            @page {
                size: landscape;
                margin: 1cm;
            }
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            padding: 0.25rem;
            font-size: 12px;
            border: 1px solid #dee2e6;
        }
        .table th {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
        .badge-print {
            font-weight: bold;
            -webkit-print-color-adjust: exact;
        }
        .text-success { color: #198754 !important; }
        .text-danger { color: #dc3545 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-info { color: #0dcaf0 !important; }
    </style>
    
    <!-- Google Fonts for Thai -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body onload="window.print()">

    <div class="text-center mb-4">
        <h3>สรุปการเข้าเรียนประจำเดือน: {{ \Carbon\Carbon::parse($month)->locale('th')->isoFormat('MMMM YYYY') }}</h3>
        @if(Auth::user()->center_id)
            <h4>{{ Auth::user()->center->name ?? '' }}</h4>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th class="text-start" style="min-width: 150px;">นักเรียน</th>
                    @for($day = 1; $day <= $endOfMonth->day; $day++)
                        <th style="width: 25px;">{{ $day }}</th>
                    @endfor
                    <th class="bg-light">มา</th>
                    <th class="bg-light">ขาด</th>
                    <th class="bg-light">ป่วย</th>
                    <th class="bg-light">ลา</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td class="text-start">
                            {{ $student->first_name }} {{ $student->last_name }}
                            @if(!Auth::user()->center_id)
                                <br><small class="text-muted">{{ $student->center->name ?? '' }}</small>
                            @endif
                        </td>
                        @for($day = 1; $day <= $endOfMonth->day; $day++)
                            @php
                                $currentDate = \Carbon\Carbon::parse($month)->day($day)->format('Y-m-d');
                                $attendance = $student->attendances->firstWhere('date', $currentDate);
                                $status = $attendance ? $attendance->status : '-';
                                $symbol = match($status) {
                                    'present' => '/',
                                    'absent' => 'ข',
                                    'sick' => 'ป',
                                    'leave' => 'ล',
                                    default => ''
                                };
                                $colorClass = match($status) {
                                    'present' => 'text-success',
                                    'absent' => 'text-danger',
                                    'sick' => 'text-warning',
                                    'leave' => 'text-info',
                                    default => ''
                                };
                            @endphp
                            <td class="{{ $colorClass }} fw-bold">{{ $symbol }}</td>
                        @endfor
                        <td class="bg-light fw-bold">{{ $student->stats['present'] }}</td>
                        <td class="bg-light fw-bold">{{ $student->stats['absent'] }}</td>
                        <td class="bg-light fw-bold">{{ $student->stats['sick'] }}</td>
                        <td class="bg-light fw-bold">{{ $student->stats['leave'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 no-print text-center">
        <button onclick="window.print()" class="btn btn-primary">พิมพ์หน้านี้</button>
        <button onclick="window.close()" class="btn btn-secondary">ปิดหน้าต่าง</button>
    </div>

</body>
</html>
