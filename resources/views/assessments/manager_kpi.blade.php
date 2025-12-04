@extends('layouts.app')

@section('title', 'สรุปคะแนน KPI')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-graph-up me-1"></i>
                    ประวัติคะแนนการประเมิน
                </h3>
            </div>
            <div class="card-body">
                <canvas id="historyChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-pie-chart me-1"></i>
                    คะแนนเฉลี่ยรายหมวดหมู่
                </h3>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    @foreach($categoryLabels as $index => $label)
                        @php
                            $score = $categoryScores[$index];
                            $maxScore = 3; 
                            $percentage = ($score / $maxScore) * 100;
                        @endphp
                        <div class="col-md-4 col-lg-3 text-center mb-4">
                            <h5 class="mb-3">{{ $label }}</h5>
                            <div style="position: relative; width: 150px; height: 150px; margin: 0 auto;">
                                <canvas id="categoryChart-{{ $index }}"></canvas>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: bold;">
                                    {{ round($percentage) }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    <i class="bi bi-list-check me-1"></i>
                    รายละเอียดคะแนน
                </h3>
                <a href="{{ route('assessments.export_pdf') }}" target="_blank" class="btn btn-sm btn-success">
                    <i class="bi bi-printer"></i> Export PDF
                </a>
            </div>
            <div class="card-body">
                <div class="accordion" id="criteriaAccordion">
                    @foreach($criteriaStats as $category => $items)
                        @php $id = md5($category); @endphp
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $id }}" aria-expanded="false" aria-controls="collapse{{ $id }}">
                                    {{ $category }}
                                </button>
                            </h2>
                            <div id="collapse{{ $id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $id }}" data-bs-parent="#criteriaAccordion">
                                <div class="accordion-body">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>เกณฑ์การประเมิน</th>
                                                <th class="text-end" style="width: 150px;">คะแนนเฉลี่ย</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $item)
                                                <tr>
                                                    <td>{{ $item->criteria_name }}</td>
                                                    <td class="text-end">
                                                        <span class="badge bg-{{ $item->avg_score >= 2.5 ? 'success' : ($item->avg_score >= 1.5 ? 'warning' : 'danger') }}">
                                                            {{ number_format($item->avg_score, 2) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // History Chart
        const historyCtx = document.getElementById('historyChart').getContext('2d');
        new Chart(historyCtx, {
            type: 'line',
            data: {
                labels: @json($historyLabels),
                datasets: [{
                    label: 'คะแนนรวม',
                    data: @json($historyScores),
                    borderColor: 'rgba(60, 141, 188, 1)',
                    backgroundColor: 'rgba(60, 141, 188, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Category Charts
        const categoryScores = @json($categoryScores);
        const maxScore = 3;
        const colors = [
            'rgba(255, 99, 132, 0.8)',   // Red
            'rgba(54, 162, 235, 0.8)',   // Blue
            'rgba(255, 206, 86, 0.8)',   // Yellow
            'rgba(75, 192, 192, 0.8)',   // Teal
            'rgba(153, 102, 255, 0.8)',  // Purple
            'rgba(255, 159, 64, 0.8)',   // Orange
            'rgba(231, 233, 237, 0.8)',  // Grey
            'rgba(220, 20, 60, 0.8)',    // Crimson
            'rgba(60, 179, 113, 0.8)',   // Medium Sea Green
            'rgba(30, 144, 255, 0.8)'    // Dodger Blue
        ];

        categoryScores.forEach((score, index) => {
            const ctx = document.getElementById(`categoryChart-${index}`).getContext('2d');
            const color = colors[index % colors.length];
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['คะแนน', 'คะแนนที่เหลือ'],
                    datasets: [{
                        data: [score, maxScore - score],
                        backgroundColor: [
                            color, 
                            'rgba(220, 220, 220, 0.5)'
                        ],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    }
                }
            });
        });
    });
</script>
@endsection
