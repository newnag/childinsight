@extends('layouts.app')

@section('title', 'แดชบอร์ด')

@section('content')
<style>
    .small-box {
        position: relative;
        display: block;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 0.25rem;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .small-box .inner {
        padding: 20px;
        z-index: 2;
        position: relative;
    }
    .small-box .inner h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
    }
    .small-box .inner p {
        font-size: 1rem;
        margin-bottom: 0;
    }
    .small-box .icon {
        color: rgba(0,0,0,0.15);
        z-index: 0;
        position: absolute;
        right: 15px;
        top: 15px;
        transition: all .3s linear;
    }
    .small-box .icon > i {
        font-size: 70px;
        line-height: 70px;
    }
    .small-box:hover .icon {
        transform: scale(1.1);
    }
    .small-box .small-box-footer {
        position: relative;
        text-align: center;
        padding: 3px 0;
        color: #fff;
        color: rgba(255,255,255,0.8);
        display: block;
        z-index: 10;
        background: rgba(0,0,0,0.1);
        text-decoration: none;
    }
    .small-box .small-box-footer:hover {
        color: #fff;
        background: rgba(0,0,0,0.15);
    }
</style>

@if(Auth::user()->role !== 'inspector')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ $data['total_students'] }}</h3>
                <p>นักเรียน</p>
            </div>
            <div class="icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <a href="{{ route('students.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                ข้อมูลเพิ่มเติม <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ $data['attendance_rate'] }}<sup style="font-size: 20px">%</sup></h3>
                <p>การเข้าเรียนวันนี้</p>
            </div>
            <div class="icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <a href="{{ route('attendance.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                เช็คชื่อ <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner text-white">
                <h3>{{ $data['latest_score'] }} <span style="font-size: 1rem; font-weight: normal;">(Avg)</span></h3>
                <p>คะแนน KPI ล่าสุด</p>
            </div>
            <div class="icon">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <a href="{{ route('assessments.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover" style="color: white !important;">
                ดูการประเมิน <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ $data['pending_maintenance'] ?? 0 }}</h3>
                <p>แจ้งซ่อมที่รอการดำเนินการ</p>
            </div>
            <div class="icon">
                <i class="bi bi-tools"></i>
            </div>
            <a href="{{ route('maintenance.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                ดูรายการแจ้งซ่อม <i class="bi bi-arrow-right-circle"></i>
            </a>
        </div>
    </div>
</div>
@endif

<div class="row">
    @if(Auth::user()->role !== 'inspector')
    <!-- Left Column -->
    <div class="col-lg-7 connectedSortable">
        <!-- Attendance Chart -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-bar-chart-fill me-1"></i>
                    แนวโน้มการเข้าเรียน (7 วันล่าสุด)
                </h3>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">เมนูด่วน</h3>
            </div>
            <div class="card-body">
                <p>คุณเข้าสู่ระบบในฐานะ <strong>{{ ucfirst(Auth::user()->role) }}</strong>.</p>
                
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('attendance.index') }}" class="btn btn-app">
                        <i class="bi bi-calendar-check fs-4 d-block mb-1"></i> การเข้าเรียน
                    </a>
                    <a href="{{ route('health.index') }}" class="btn btn-app">
                        <i class="bi bi-heart-pulse fs-4 d-block mb-1"></i> สุขภาพ
                    </a>
                    <a href="{{ route('assessments.index') }}" class="btn btn-app">
                        <i class="bi bi-clipboard-check fs-4 d-block mb-1"></i> การประเมิน
                    </a>
                    <a href="{{ route('maintenance.index') }}" class="btn btn-app">
                        <i class="bi bi-tools fs-4 d-block mb-1"></i> แจ้งซ่อม
                    </a>
                    <a href="{{ route('attendance.report') }}" class="btn btn-app">
                        <i class="bi bi-file-earmark-bar-graph fs-4 d-block mb-1"></i> รายงาน
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Right Column -->
    <div class="{{ Auth::user()->role === 'inspector' ? 'col-lg-12' : 'col-lg-5' }} connectedSortable">
        <!-- Ranking -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-trophy-fill me-1"></i>
                    อันดับศูนย์เด็กเล็ก (KPI)
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>ศูนย์</th>
                            <th>คะแนน</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ranking as $index => $rank)
                        <tr>
                            <td>{{ $index + 1 }}.</td>
                            <td>{{ $rank['name'] }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $rank['score'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->role !== 'inspector')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($data['chart_dates'] ?? []),
                datasets: [{
                    label: 'อัตราการเข้าเรียน (%)',
                    data: @json($data['chart_rates'] ?? []),
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    });
</script>
@endif
@endsection


