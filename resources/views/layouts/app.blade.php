<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Qlinic') - Qlinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6C63FF;
            --primary-soft: #8B83FF;
            --primary-bg: rgba(108, 99, 255, 0.08);
            --primary-border: rgba(108, 99, 255, 0.18);
            --accent: #00C9A7;
            --accent-soft: #33D4B8;
            --rose: #FF6B8A;
            --amber: #FFB347;
            --sky: #47B5FF;
            --bg: #F4F6FB;
            --bg-white: #FFFFFF;
            --bg-card: #FFFFFF;
            --bg-sidebar: #FFFFFF;
            --text: #1A1D2E;
            --text-secondary: #6B7194;
            --text-muted: #9CA3C5;
            --border: #E8ECF4;
            --border-light: #F0F2F8;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 20px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 40px rgba(0,0,0,0.08);
            --sidebar-width: 260px;
            --radius: 14px;
            --radius-sm: 10px;
        }
        * {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
        }
        body {
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ─── Sidebar ─── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            z-index: 1000;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 1.75rem 1.5rem 1.25rem;
        }
        .sidebar-brand h4 {
            margin: 0;
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -0.5px;
        }
        .sidebar-brand h4 .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-soft));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }
        .sidebar-brand small {
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 500;
            display: block;
            margin-top: 0.25rem;
            margin-left: 2.85rem;
        }
        .sidebar-nav {
            flex: 1;
            padding: 0.5rem 0.75rem;
        }
        .nav-section {
            padding: 1rem 0.75rem 0.4rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
        }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.85rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            border-radius: var(--radius-sm);
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }
        .sidebar-nav .nav-link:hover {
            color: var(--primary);
            background: var(--primary-bg);
        }
        .sidebar-nav .nav-link.active {
            color: var(--primary);
            background: var(--primary-bg);
            font-weight: 600;
        }
        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        /* User card at bottom */
        .sidebar-user {
            padding: 1rem 1.25rem;
            margin: 0.75rem;
            border-radius: var(--radius);
            background: var(--bg);
        }
        .sidebar-user .user-info {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }
        .sidebar-user .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            color: white;
            flex-shrink: 0;
        }
        .sidebar-user .user-name {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text);
        }
        .sidebar-user .user-role {
            font-size: 0.65rem;
            color: var(--text-muted);
            text-transform: capitalize;
        }
        .btn-logout {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            border-radius: 8px;
            padding: 0.35rem 0.5rem;
            transition: all 0.2s;
        }
        .btn-logout:hover {
            background: #FFF0F0;
            border-color: var(--rose);
            color: var(--rose);
        }

        /* ─── Main ─── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 2rem 2.5rem;
        }

        /* ─── Cards ─── */
        .card-modern {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: all 0.25s ease;
        }
        .card-modern:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--border-light);
        }
        .card-modern .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-light);
            padding: 1.1rem 1.35rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text);
        }
        .card-modern .card-body {
            padding: 1.35rem;
        }

        /* ─── Page Header ─── */
        .page-header {
            margin-bottom: 1.75rem;
        }
        .page-header h2 {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text);
            margin-bottom: 0.2rem;
            letter-spacing: -0.3px;
        }
        .page-header p {
            color: var(--text-secondary);
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        /* ─── Badges ─── */
        .badge-status {
            padding: 0.35em 0.8em;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.7rem;
            letter-spacing: 0.2px;
        }
        .badge-menunggu { background: #FFF3E0; color: #E65100; }
        .badge-diperiksa { background: #EDE7F6; color: #5E35B1; }
        .badge-selesai { background: #E8F5E9; color: #2E7D32; }
        .badge-dilewati { background: #FFF0F0; color: #D32F2F; }
        .badge-dibatalkan { background: #F5F5F5; color: #757575; }

        /* ─── Tables ─── */
        .table-modern {
            color: var(--text);
            margin: 0;
        }
        .table-modern thead th {
            background: var(--bg);
            border-color: var(--border-light);
            font-weight: 600;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            padding: 0.85rem 1rem;
        }
        .table-modern tbody td {
            border-color: var(--border-light);
            padding: 0.85rem 1rem;
            vertical-align: middle;
            font-size: 0.87rem;
        }
        .table-modern tbody tr {
            transition: background 0.15s ease;
        }
        .table-modern tbody tr:hover {
            background: rgba(108, 99, 255, 0.02);
        }

        /* ─── Buttons ─── */
        .btn {
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background: var(--primary-soft);
            border-color: var(--primary-soft);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
        }
        .btn-success {
            background: var(--accent);
            border-color: var(--accent);
        }
        .btn-success:hover {
            background: var(--accent-soft);
            border-color: var(--accent-soft);
        }
        .btn-sm {
            border-radius: 8px;
            font-size: 0.78rem;
            padding: 0.35rem 0.75rem;
        }
        .btn-soft {
            background: var(--primary-bg);
            border: 1px solid var(--primary-border);
            color: var(--primary);
        }
        .btn-soft:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        .btn-glass {
            background: var(--primary-bg);
            border: 1px solid var(--primary-border);
            color: var(--primary);
        }
        .btn-glass:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* ─── Stat Cards ─── */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.35rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .stat-card .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }
        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            margin-top: 0.65rem;
            color: var(--text);
        }
        .stat-card .stat-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ─── Forms ─── */
        .form-control, .form-select {
            background: var(--bg);
            border-color: var(--border);
            color: var(--text);
            border-radius: var(--radius-sm);
            font-size: 0.87rem;
            padding: 0.6rem 0.85rem;
        }
        .form-control:focus, .form-select:focus {
            background: var(--bg-white);
            border-color: var(--primary);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
        }
        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 0.4rem;
        }

        /* ─── Queue Number ─── */
        .queue-number {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -1px;
        }

        /* ─── Pulse ─── */
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(94, 53, 177, 0.5); }
            70% { box-shadow: 0 0 0 8px rgba(94, 53, 177, 0); }
            100% { box-shadow: 0 0 0 0 rgba(94, 53, 177, 0); }
        }

        /* ─── Refresh Indicator ─── */
        .refresh-indicator {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 500;
        }
        .refresh-indicator .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            animation: blink 2s infinite;
        }
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }

        /* ─── Alerts ─── */
        .alert {
            border-radius: var(--radius);
            border: none;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* ─── Modals ─── */
        .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
        }
        .modal-header {
            border-bottom-color: var(--border-light);
            padding: 1.25rem 1.5rem;
        }
        .modal-footer {
            border-top-color: var(--border-light);
        }
        .modal-title {
            font-weight: 700;
            font-size: 1rem;
        }

        /* ─── Room Cards ─── */
        .room-card {
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background: var(--bg-card);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
        }
        .room-card:hover {
            box-shadow: var(--shadow-md);
        }
        .room-card-header {
            padding: 1rem 1.15rem;
            background: var(--bg);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .room-card-header h6 {
            margin: 0;
            font-weight: 700;
            font-size: 0.88rem;
        }

        /* ─── Shift Badges ─── */
        .badge-shift-pagi {
            background: #FFF8E1;
            color: #F57F17;
            font-weight: 600;
        }
        .badge-shift-sore {
            background: #E3F2FD;
            color: #1565C0;
            font-weight: 600;
        }
        .badge-shift-malam {
            background: #EDE7F6;
            color: #4527A0;
            font-weight: 600;
        }

        /* ─── Progress bars ─── */
        .progress {
            border-radius: 50px;
            overflow: hidden;
        }
        .progress-bar {
            border-radius: 50px;
        }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ─── Dropdown  ─── */
        .dropdown-menu {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-md);
            padding: 0.35rem;
        }
        .dropdown-item {
            border-radius: 8px;
            font-size: 0.83rem;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }
        .dropdown-item:hover {
            background: var(--primary-bg);
            color: var(--primary);
        }

        /* ─── Empty state ─── */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        .empty-state i {
            font-size: 2.5rem;
            color: var(--border);
        }
        .empty-state h5 {
            margin-top: 0.75rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-secondary);
        }
        .empty-state p {
            color: var(--text-muted);
            font-size: 0.83rem;
        }

        /* ─── Live Clock Bar ─── */
        .live-clock-bar {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        .live-clock-bar .clock-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .live-clock-bar .clock-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }
        .live-clock-bar .clock-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            line-height: 1;
        }
        .live-clock-bar .clock-value {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }
        .live-clock-bar .clock-separator {
            width: 1px;
            height: 28px;
            background: var(--border);
        }
        .live-clock-bar .shift-badge {
            padding: 0.3em 0.75em;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.75rem;
        }
        .live-clock-bar .shift-pagi { background: #FFF8E1; color: #F57F17; }
        .live-clock-bar .shift-sore { background: #E3F2FD; color: #1565C0; }
        .live-clock-bar .shift-malam { background: #EDE7F6; color: #4527A0; }
        .live-clock-bar .shift-diluar { background: #FFF0F0; color: #D32F2F; }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h4>
                <span class="brand-icon"><i class="bi bi-heart-pulse-fill"></i></span>
                Qlinic
            </h4>
            <small>Sistem Klinik Dokter Umum</small>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Menu</div>

            @if(auth()->user()->role === 'patient')
                <a class="nav-link {{ request()->routeIs('patient.queue*') ? 'active' : '' }}" href="{{ route('patient.queue') }}">
                    <i class="bi bi-list-ol"></i> Status Antrean
                </a>
                <a class="nav-link {{ request()->routeIs('patient.schedule*') ? 'active' : '' }}" href="{{ route('patient.schedule') }}">
                    <i class="bi bi-calendar2-week"></i> Jadwal Dokter
                </a>
                <a class="nav-link {{ request()->routeIs('patient.medical-records*') ? 'active' : '' }}" href="{{ route('patient.medical-records') }}">
                    <i class="bi bi-file-earmark-medical"></i> Rekam Medis
                </a>
            @elseif(auth()->user()->role === 'doctor')
                <a class="nav-link {{ request()->routeIs('doctor.queue*') ? 'active' : '' }}" href="{{ route('doctor.queue') }}">
                    <i class="bi bi-people"></i> Daftar Pasien
                </a>
                <a class="nav-link {{ request()->routeIs('doctor.schedule*') ? 'active' : '' }}" href="{{ route('doctor.schedule') }}">
                    <i class="bi bi-calendar2-week"></i> Jadwal Dokter
                </a>
                <a class="nav-link {{ request()->routeIs('doctor.medical-records*') ? 'active' : '' }}" href="{{ route('doctor.medical-records') }}">
                    <i class="bi bi-file-earmark-medical"></i> Rekam Medis
                </a>
            @elseif(auth()->user()->role === 'admin')
                <a class="nav-link {{ request()->routeIs('admin.queue*') ? 'active' : '' }}" href="{{ route('admin.queue') }}">
                    <i class="bi bi-grid-3x3-gap"></i> Manajemen Antrean
                </a>
                <a class="nav-link {{ request()->routeIs('admin.schedule*') ? 'active' : '' }}" href="{{ route('admin.schedule') }}">
                    <i class="bi bi-calendar2-week"></i> Jadwal Dokter
                </a>
                <a class="nav-link {{ request()->routeIs('admin.medical-records*') ? 'active' : '' }}" href="{{ route('admin.medical-records') }}">
                    <i class="bi bi-file-earmark-medical"></i> Rekam Medis
                </a>
            @endif
        </nav>

        <div class="sidebar-user">
            <div class="d-flex justify-content-between align-items-center">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">
                            {{ auth()->user()->role === 'patient' ? 'Pasien' : (auth()->user()->role === 'doctor' ? 'Dokter' : 'Admin') }}
                        </div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout" title="Keluar">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <!-- Main Content -->
    <div class="{{ auth()->check() ? 'main-content' : '' }}">
        @auth
        <!-- Live Clock Bar -->
        <div class="live-clock-bar" id="liveClockBar">
            <div class="clock-item">
                <div class="clock-icon" style="background: rgba(108,99,255,0.1); color: var(--primary);">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div>
                    <div class="clock-label">Hari</div>
                    <div class="clock-value" id="clockDay">-</div>
                </div>
            </div>
            <div class="clock-separator"></div>
            <div class="clock-item">
                <div class="clock-icon" style="background: rgba(0,201,167,0.1); color: var(--accent);">
                    <i class="bi bi-clock"></i>
                </div>
                <div>
                    <div class="clock-label">Jam</div>
                    <div class="clock-value" id="clockTime">-</div>
                </div>
            </div>
            <div class="clock-separator"></div>
            <div class="clock-item">
                <div class="clock-icon" style="background: rgba(255,107,138,0.1); color: var(--rose);">
                    <i class="bi bi-brightness-high"></i>
                </div>
                <div>
                    <div class="clock-label">Shift Aktif</div>
                    <div class="clock-value"><span class="shift-badge" id="clockShift">-</span></div>
                </div>
            </div>
            <div class="clock-separator"></div>
            <div class="clock-item">
                <div class="clock-icon" style="background: rgba(71,181,255,0.1); color: var(--sky);">
                    <i class="bi bi-calendar-date"></i>
                </div>
                <div>
                    <div class="clock-label">Tanggal</div>
                    <div class="clock-value" id="clockDate">-</div>
                </div>
            </div>
        </div>
        @endauth

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @auth
    <script>
        function updateClock() {
            const now = new Date();
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            const hour = now.getHours();
            const min = String(now.getMinutes()).padStart(2,'0');
            const sec = String(now.getSeconds()).padStart(2,'0');

            // Day
            document.getElementById('clockDay').textContent = days[now.getDay()];

            // Time
            document.getElementById('clockTime').textContent = String(hour).padStart(2,'0') + ':' + min + ':' + sec;

            // Date
            document.getElementById('clockDate').textContent = now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();

            // Shift — must match server logic in Schedule::getCurrentShift()
            let shift, cls;
            if (hour >= 8 && hour < 14) {
                shift = 'Pagi (08:00-14:00)';
                cls = 'shift-pagi';
            } else if (hour >= 15 && hour < 21) {
                shift = 'Sore (15:00-21:00)';
                cls = 'shift-sore';
            } else if (hour >= 22 || hour < 4) {
                shift = 'Malam (22:00-04:00)';
                cls = 'shift-malam';
            } else {
                shift = 'Di Luar Jam Operasional';
                cls = 'shift-diluar';
            }

            const badge = document.getElementById('clockShift');
            badge.textContent = shift;
            badge.className = 'shift-badge ' + cls;
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>
    @endauth
    @stack('scripts')
</body>
</html>
