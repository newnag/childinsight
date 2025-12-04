@extends('layouts.app')

@section('title', 'รายงานการเข้าเรียน')

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

<div class="row mb-3">
    <div class="col-md-6">
        <form action="{{ route('attendance.report') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="month" class="col-form-label">เดือน:</label>
            </div>
            <div class="col-auto">
                <input type="text" id="month" name="month" class="form-control month-picker" value="{{ $month }}" onchange="this.form.submit()" placeholder="เลือกเดือน...">
            </div>
        </form>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('attendance.report_pdf', ['month' => $month]) }}" target="_blank" class="btn btn-success">
            <i class="bi bi-printer"></i> พิมพ์ / ส่งออก PDF
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ $summary['present'] }}</h3>
                <p>มาเรียน</p>
            </div>
            <div class="icon"><i class="bi bi-check-circle"></i></div>
            <div class="small-box-footer">&nbsp;</div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ $summary['absent'] }}</h3>
                <p>ขาดเรียน</p>
            </div>
            <div class="icon"><i class="bi bi-x-circle"></i></div>
            <div class="small-box-footer">&nbsp;</div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner text-white">
                <h3>{{ $summary['sick'] }}</h3>
                <p>ป่วย</p>
            </div>
            <div class="icon"><i class="bi bi-thermometer-half"></i></div>
            <div class="small-box-footer">&nbsp;</div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3>{{ $summary['leave'] }}</h3>
                <p>ลา</p>
            </div>
            <div class="icon"><i class="bi bi-envelope"></i></div>
            <div class="small-box-footer">&nbsp;</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">สรุปการเข้าเรียนประจำเดือน: {{ \Carbon\Carbon::parse($month)->locale('th')->isoFormat('MMMM YYYY') }}</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm text-center" style="font-size: 0.9rem;">
                <thead>
                    <tr>
                        <th class="text-start" style="min-width: 150px;">นักเรียน</th>
                        @for($day = 1; $day <= $endOfMonth->day; $day++)
                            <th style="width: 30px;">{{ $day }}</th>
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
                            <td class="text-start">{{ $student->first_name }} {{ $student->last_name }}</td>
                            @for($day = 1; $day <= $endOfMonth->day; $day++)
                                @php
                                    $currentDate = \Carbon\Carbon::parse($month)->day($day)->format('Y-m-d');
                                    $attendance = $student->attendances->firstWhere('date', $currentDate);
                                    $status = $attendance ? $attendance->status : '-';
                                    $badgeClass = match($status) {
                                        'present' => 'text-success fw-bold',
                                        'absent' => 'text-danger fw-bold',
                                        'sick' => 'text-warning fw-bold',
                                        'leave' => 'text-info fw-bold',
                                        default => 'text-muted'
                                    };
                                    $symbol = match($status) {
                                        'present' => '/',
                                        'absent' => 'ข',
                                        'sick' => 'ป',
                                        'leave' => 'ล',
                                        default => '-'
                                    };
                                @endphp
                                <td class="{{ $badgeClass }}">{{ $symbol }}</td>
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
    </div>
</div>
@endsection
