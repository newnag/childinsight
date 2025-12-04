@extends('layouts.app')

@section('title', 'ประวัติสุขภาพ: ' . $student->first_name)

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header">ข้อมูลนักเรียน</div>
            <div class="card-body">
                <p><strong>ชื่อ:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
                <p><strong>อายุ:</strong> {{ \Carbon\Carbon::parse($student->dob)->age }} ปี</p>
                <p><strong>ผู้ปกครอง:</strong> {{ $student->parent_name }}</p>
                <p><strong>เบอร์โทรศัพท์:</strong> {{ $student->parent_contact }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">บันทึกล่าสุด</div>
            <div class="list-group list-group-flush">
                @foreach($records as $record)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ \Carbon\Carbon::parse($record->recorded_at)->format('d M Y') }}</h6>
                            <small class="text-muted">BMI: {{ $record->bmi }}</small>
                        </div>
                        <p class="mb-1">
                            น้ำหนัก: {{ $record->weight }} กก. | ส่วนสูง: {{ $record->height }} ซม.
                        </p>
                        <small class="badge bg-{{ $record->nutrition_status == 'normal' ? 'success' : ($record->nutrition_status == 'thin' ? 'warning' : 'danger') }}">
                            {{ ucfirst($record->nutrition_status) }}
                        </small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header">กราฟการเจริญเติบโต</div>
            <div class="card-body">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">ประวัติพัฒนาการ</div>
            <div class="card-body">
                @if(isset($developmentRecords) && $developmentRecords->count() > 0)
                    <div class="timeline">
                        @foreach($developmentRecords as $dev)
                            <div class="mb-4 border-bottom pb-3">
                                <h6 class="text-primary fw-bold">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    {{ \Carbon\Carbon::parse($dev->recorded_at)->format('d M Y') }}
                                </h6>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <strong>ร่างกาย:</strong>
                                        <p class="text-muted small">{{ $dev->physical_desc ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>อารมณ์:</strong>
                                        <p class="text-muted small">{{ $dev->emotional_desc ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>พฤติกรรม:</strong>
                                        <p class="text-muted small">{{ $dev->behavior_desc ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>การเรียนรู้:</strong>
                                        <p class="text-muted small">{{ $dev->learning_desc ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-muted my-4">ไม่พบประวัติพัฒนาการ</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('growthChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.dates,
                datasets: [
                    {
                        label: 'น้ำหนัก (กก.)',
                        data: chartData.weights,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'ส่วนสูง (ซม.)',
                        data: chartData.heights,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'น้ำหนัก (กก.)' }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'ส่วนสูง (ซม.)' },
                        grid: {
                            drawOnChartArea: false,
                        },
                    },
                }
            }
        });
    });
</script>
@endsection
