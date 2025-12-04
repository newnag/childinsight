<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ChildInsight') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @auth
            <!-- Header -->
            <nav class="app-header navbar navbar-expand bg-body">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                                <i class="bi bi-list"></i>
                            </a>
                        </li>
                        <li class="nav-item d-none d-md-block">
                            <a href="{{ url('/') }}" class="nav-link">หน้าหลัก</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        ออกจากระบบ
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Sidebar -->
            <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
                <div class="sidebar-brand">
                    <a href="{{ url('/') }}" class="brand-link">
                        <span class="brand-text fw-light">ChildInsight</span>
                    </a>
                </div>
                <div class="sidebar-wrapper">
                    <nav class="mt-2">
                        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-speedometer"></i>
                                    <p>แดชบอร์ด</p>
                                </a>
                            </li>
                            
                            @if(Auth::user()->role !== 'inspector' && Auth::user()->role !== 'manager')
                            <li class="nav-header">การจัดการ</li>
                            
                            <li class="nav-item">
                                <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>นักเรียน</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('attendance.index') }}" class="nav-link {{ (request()->routeIs('attendance.*') && !request()->routeIs('attendance.report')) ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-calendar-check"></i>
                                    <p>การเข้าเรียน</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('health.index') }}" class="nav-link {{ request()->routeIs('health.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-heart-pulse"></i>
                                    <p>สุขภาพและพัฒนาการ</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('daily_logs.index') }}" class="nav-link {{ request()->routeIs('daily_logs.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-journal-text"></i>
                                    <p>บันทึกประจำวัน</p>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role !== 'manager')
                            <li class="nav-item">
                                <a href="{{ route('assessments.index') }}" class="nav-link {{ request()->routeIs('assessments.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-clipboard-check"></i>
                                    <p>การประเมินศูนย์</p>
                                </a>
                            </li>
                            @else
                            <li class="nav-item">
                                <a href="{{ route('assessments.index') }}" class="nav-link {{ request()->routeIs('assessments.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-clipboard-data"></i>
                                    <p>คะแนน KPI</p>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role !== 'inspector')
                            <li class="nav-item">
                                <a href="{{ route('maintenance.index') }}" class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-tools"></i>
                                    <p>แจ้งซ่อมบำรุง</p>
                                </a>
                            </li>

                            <li class="nav-header">รายงาน</li>
                            
                            <li class="nav-item">
                                <a href="{{ route('attendance.report') }}" class="nav-link {{ request()->routeIs('attendance.report') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
                                    <p>รายงานการเข้าเรียน</p>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role === 'admin')
                            <li class="nav-header">ผู้ดูแลระบบ</li>
                            <li class="nav-item">
                                <a href="{{ route('centers.index') }}" class="nav-link {{ request()->routeIs('centers.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-building"></i>
                                    <p>ศูนย์เด็กเล็ก</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('assessment_criterias.index') }}" class="nav-link {{ request()->routeIs('assessment_criterias.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-list-check"></i>
                                    <p>เกณฑ์การประเมิน</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </aside>
        @endauth

        <!-- Main Content -->
        <main class="app-main">
            @if(!Auth::check())
                <div class="container py-5">
                    @yield('content')
                </div>
            @else
                <div class="app-content-header">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="mb-0">@yield('title', 'Dashboard')</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
            @endif
        </main>

        @auth
            <footer class="app-footer">
                <div class="float-end d-none d-sm-inline">ChildInsight System</div>
                <strong>Copyright &copy; 2025.</strong> All rights reserved.
            </footer>
        @endauth
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // General Date Picker
            flatpickr("input[type=date]", {
                locale: "th",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                onChange: function(selectedDates, dateStr, instance) {
                    const event = new Event('change', { bubbles: true });
                    instance.element.dispatchEvent(event);
                }
            });

            // Month Picker
            flatpickr(".month-picker", {
                locale: "th",
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, //defaults to false
                        dateFormat: "Y-m", //defaults to "F Y"
                        altFormat: "F Y", //defaults to "F Y"
                        theme: "light" // defaults to "light"
                    })
                ],
                altInput: true,
                onChange: function(selectedDates, dateStr, instance) {
                    const event = new Event('change', { bubbles: true });
                    instance.element.dispatchEvent(event);
                }
            });
        });
    </script>
</body>
</html>

