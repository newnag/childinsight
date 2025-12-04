<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานคะแนน KPI - {{ $center->name }}</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts for Thai -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #fff;
            padding: 20px;
        }
        @media print {
            @page {
                size: A4;
                margin: 1.5cm;
            }
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .table th {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }
            .badge {
                border: 1px solid #000;
                color: #000 !important;
                background: none !important;
            }
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 0.5rem;
            border: 1px solid #dee2e6;
        }
        .category-header {
            background-color: #e9ecef;
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h3>รายงานสรุปคะแนน KPI</h3>
        <h4>{{ $center->name }}</h4>
        <p>วันที่พิมพ์: {{ \Carbon\Carbon::now()->locale('th')->isoFormat('D MMMM YYYY') }}</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>เกณฑ์การประเมิน</th>
                    <th class="text-end" style="width: 150px;">คะแนนเฉลี่ย (เต็ม 3)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($criteriaStats as $category => $items)
                    <tr class="category-header">
                        <td colspan="2">{{ $category }}</td>
                    </tr>
                    @foreach($items as $item)
                        <tr>
                            <td style="padding-left: 20px;">{{ $item->criteria_name }}</td>
                            <td class="text-end">
                                {{ number_format($item->avg_score, 2) }}
                            </td>
                        </tr>
                    @endforeach
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
