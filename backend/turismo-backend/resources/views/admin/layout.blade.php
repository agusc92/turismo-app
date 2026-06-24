<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel de administración - Turismo App">
    <title>@yield('title', 'Admin') | Turismo Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-base:      #0a0f1e;
            --bg-surface:   #111827;
            --bg-card:      #1a2234;
            --bg-hover:     #1e2d45;
            --border:       rgba(56, 189, 248, 0.12);
            --border-hover: rgba(56, 189, 248, 0.3);
            --primary:      #38bdf8;
            --primary-dark: #0ea5e9;
            --primary-glow: rgba(56, 189, 248, 0.15);
            --accent:       #818cf8;
            --success:      #34d399;
            --warning:      #fbbf24;
            --danger:       #f87171;
            --text-primary: #f1f5f9;
            --text-muted:   #94a3b8;
            --text-faint:   #475569;
            --sidebar-w:    260px;
            --topbar-h:     64px;
            --radius:       12px;
            --radius-sm:    8px;
            --shadow:       0 4px 24px rgba(0,0,0,0.4);
            --transition:   0.2s ease;
        }

        html, body { height: 100%; font-family: 'Inter', sans-serif; background: var(--bg-base); color: var(--text-primary); }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-hover); border-radius: 3px; }

        /* ── Layout ── */
        .layout { display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            transition: transform var(--transition);
        }
        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid var(--border);
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            box-shadow: 0 0 20px var(--primary-glow);
        }
        .brand-text { font-size: 16px; font-weight: 700; color: var(--text-primary); }
        .brand-sub { font-size: 11px; color: var(--text-muted); margin-top: 1px; }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
        .nav-section { margin-bottom: 24px; }
        .nav-label {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 0 8px;
            margin-bottom: 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all var(--transition);
            margin-bottom: 2px;
            position: relative;
            overflow: hidden;
        }
        .nav-item:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }
        .nav-item.active {
            background: var(--primary-glow);
            color: var(--primary);
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--primary);
            border-radius: 0 3px 3px 0;
        }
        .nav-icon { font-size: 17px; width: 20px; text-align: center; flex-shrink: 0; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--border);
        }
        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            margin-bottom: 8px;
        }
        .admin-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--primary-dark), var(--accent));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: white;
            flex-shrink: 0;
        }
        .admin-name { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .admin-role { font-size: 11px; color: var(--text-muted); }
        .btn-logout {
            width: 100%;
            padding: 8px 12px;
            background: transparent;
            border: 1px solid rgba(248,113,113,0.3);
            color: var(--danger);
            border-radius: var(--radius-sm);
            font-size: 13px;
            cursor: pointer;
            transition: all var(--transition);
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .btn-logout:hover { background: rgba(248,113,113,0.1); border-color: var(--danger); }

        /* ── Main ── */
        .main { margin-left: var(--sidebar-w); flex: 1; min-height: 100vh; }
        .topbar {
            height: var(--topbar-h);
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            position: sticky; top: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
        }
        .topbar-title { font-size: 20px; font-weight: 700; }
        .topbar-breadcrumb { font-size: 13px; color: var(--text-muted); margin-left: 4px; }

        .content { padding: 28px; }

        /* ── Cards ── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .card-title { font-size: 15px; font-weight: 600; }
        .card-body { padding: 22px; }

        /* ── Stat Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 18px;
            transition: all var(--transition);
            cursor: pointer;
            text-decoration: none;
            display: block;
        }
        .stat-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        .stat-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }
        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 600;
            border: none; cursor: pointer;
            transition: all var(--transition);
            white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
            color: #0a0f1e;
            box-shadow: 0 0 20px rgba(56,189,248,0.25);
        }
        .btn-primary:hover {
            box-shadow: 0 0 30px rgba(56,189,248,0.4);
            transform: translateY(-1px);
        }
        .btn-danger { background: rgba(248,113,113,0.15); color: var(--danger); border: 1px solid rgba(248,113,113,0.3); }
        .btn-danger:hover { background: rgba(248,113,113,0.25); }
        .btn-secondary { background: var(--bg-hover); color: var(--text-muted); border: 1px solid var(--border); }
        .btn-secondary:hover { color: var(--text-primary); border-color: var(--border-hover); }
        .btn-success { background: rgba(52,211,153,0.15); color: var(--success); border: 1px solid rgba(52,211,153,0.3); }
        .btn-success:hover { background: rgba(52,211,153,0.25); }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-icon { padding: 6px 8px; }

        /* ── Search ── */
        .search-bar {
            position: relative;
            flex: 1;
            max-width: 360px;
        }
        .search-bar input {
            width: 100%;
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            padding: 8px 12px 8px 36px;
            font-size: 13px;
            transition: border-color var(--transition);
        }
        .search-bar input:focus { outline: none; border-color: var(--primary); }
        .search-bar input::placeholder { color: var(--text-faint); }
        .search-icon {
            position: absolute;
            left: 10px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-faint);
            font-size: 14px;
            pointer-events: none;
        }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-faint);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid rgba(56,189,248,0.06); transition: background var(--transition); }
        tbody tr:hover { background: var(--bg-hover); }
        tbody tr:last-child { border-bottom: none; }
        td { padding: 12px 14px; color: var(--text-primary); vertical-align: middle; }
        td.muted { color: var(--text-muted); font-size: 12px; }

        .table-img {
            width: 38px; height: 38px;
            border-radius: 8px;
            object-fit: cover;
            background: var(--bg-base);
        }
        .table-img-placeholder {
            width: 38px; height: 38px;
            border-radius: 8px;
            background: var(--bg-base);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            color: var(--text-faint);
        }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 8px;
            border-radius: 99px;
            font-size: 11px; font-weight: 600;
        }
        .badge-blue { background: rgba(56,189,248,0.15); color: var(--primary); }
        .badge-green { background: rgba(52,211,153,0.15); color: var(--success); }
        .badge-red { background: rgba(248,113,113,0.15); color: var(--danger); }
        .badge-yellow { background: rgba(251,191,36,0.15); color: var(--warning); }
        .badge-purple { background: rgba(129,140,248,0.15); color: var(--accent); }

        /* ── Pagination ── */
        .pagination-wrap { display: flex; align-items: center; justify-content: space-between; padding: 14px 22px; border-top: 1px solid var(--border); font-size: 13px; color: var(--text-muted); }
        .pagination { display: flex; gap: 4px; }
        .page-btn { padding: 5px 10px; border-radius: 6px; border: 1px solid var(--border); background: transparent; color: var(--text-muted); cursor: pointer; font-size: 13px; transition: all var(--transition); }
        .page-btn:hover { border-color: var(--border-hover); color: var(--text-primary); }
        .page-btn.active { background: var(--primary-glow); border-color: var(--primary); color: var(--primary); }

        /* ── Modal ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.75);
            backdrop-filter: blur(4px);
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.15s ease;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .modal {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.2s ease;
            box-shadow: 0 25px 80px rgba(0,0,0,0.6);
        }
        .modal-lg { max-width: 760px; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .modal-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-title { font-size: 16px; font-weight: 700; }
        .modal-close {
            background: none; border: none;
            color: var(--text-faint); font-size: 20px;
            cursor: pointer; line-height: 1;
            transition: color var(--transition);
            padding: 2px 6px;
        }
        .modal-close:hover { color: var(--danger); }
        .modal-body { padding: 22px; }
        .modal-footer { padding: 16px 22px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }

        /* ── Forms ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-1 { grid-template-columns: 1fr; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.span-2 { grid-column: 1 / -1; }
        label { font-size: 12px; font-weight: 600; color: var(--text-muted); }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        input[type="url"],
        textarea,
        select {
            background: var(--bg-base);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            padding: 9px 12px;
            font-size: 13px;
            font-family: inherit;
            transition: border-color var(--transition);
            width: 100%;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        input::placeholder, textarea::placeholder { color: var(--text-faint); }
        textarea { resize: vertical; min-height: 80px; }
        select option { background: var(--bg-card); }

        .toggle-wrap { display: flex; align-items: center; gap: 10px; }
        .toggle {
            position: relative;
            width: 40px; height: 22px;
            cursor: pointer;
        }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; inset: 0;
            background: var(--bg-hover);
            border: 1px solid var(--border);
            border-radius: 99px;
            transition: all var(--transition);
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 16px; height: 16px;
            left: 2px; top: 2px;
            background: var(--text-faint);
            border-radius: 50%;
            transition: all var(--transition);
        }
        .toggle input:checked + .toggle-slider { background: var(--primary-glow); border-color: var(--primary); }
        .toggle input:checked + .toggle-slider::before { transform: translateX(18px); background: var(--primary); }

        .error-msg { font-size: 11px; color: var(--danger); }

        /* ── Delete confirmation ── */
        .confirm-modal { max-width: 420px; }
        .confirm-icon { font-size: 40px; text-align: center; margin-bottom: 12px; }
        .confirm-text { text-align: center; color: var(--text-muted); font-size: 14px; line-height: 1.5; }
        .confirm-name { font-weight: 700; color: var(--text-primary); }

        /* ── Toast ── */
        .toast-container {
            position: fixed; bottom: 24px; right: 24px;
            z-index: 300;
            display: flex; flex-direction: column; gap: 8px;
        }
        .toast {
            padding: 12px 18px;
            border-radius: var(--radius-sm);
            font-size: 13px; font-weight: 500;
            display: flex; align-items: center; gap: 8px;
            animation: slideLeft 0.3s ease;
            min-width: 240px;
            box-shadow: var(--shadow);
        }
        @keyframes slideLeft { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        .toast-success { background: rgba(52,211,153,0.15); border: 1px solid rgba(52,211,153,0.3); color: var(--success); }
        .toast-error { background: rgba(248,113,113,0.15); border: 1px solid rgba(248,113,113,0.3); color: var(--danger); }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 48px 24px; color: var(--text-muted); }
        .empty-icon { font-size: 48px; margin-bottom: 12px; opacity: 0.4; }
        .empty-text { font-size: 14px; }

        /* ── Loading ── */
        .loading-row td { text-align: center; padding: 32px; color: var(--text-faint); }
        .spinner {
            display: inline-block;
            width: 20px; height: 20px;
            border: 2px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
        }

        /* ── Livewire loading indicator ── */
        [wire\:loading] { opacity: 0.6; pointer-events: none; }
    </style>
</head>
<body>
<div class="layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <div class="brand-icon">🌊</div>
                <div>
                    <div class="brand-text">TurismoApp</div>
                    <div class="brand-sub">Panel de Admin</div>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-label">General</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span> Dashboard
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-label">Recursos Turísticos</div>
                <a href="{{ route('admin.eventos') }}" class="nav-item {{ request()->routeIs('admin.eventos') ? 'active' : '' }}">
                    <span class="nav-icon">🎉</span> Eventos
                </a>
                <a href="{{ route('admin.actividades') }}" class="nav-item {{ request()->routeIs('admin.actividades') ? 'active' : '' }}">
                    <span class="nav-icon">🏄</span> Actividades
                </a>
                <a href="{{ route('admin.alojamientos') }}" class="nav-item {{ request()->routeIs('admin.alojamientos') ? 'active' : '' }}">
                    <span class="nav-icon">🏨</span> Alojamientos
                </a>
                <a href="{{ route('admin.balnearios') }}" class="nav-item {{ request()->routeIs('admin.balnearios') ? 'active' : '' }}">
                    <span class="nav-icon">🏖️</span> Balnearios
                </a>
                <a href="{{ route('admin.gastronomicos') }}" class="nav-item {{ request()->routeIs('admin.gastronomicos') ? 'active' : '' }}">
                    <span class="nav-icon">🍽️</span> Gastronómicos
                </a>
                <a href="{{ route('admin.complejos') }}" class="nav-item {{ request()->routeIs('admin.complejos') ? 'active' : '' }}">
                    <span class="nav-icon">🏢</span> Complejos
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-label">Configuración</div>
                <a href="{{ route('admin.tipos') }}" class="nav-item {{ request()->routeIs('admin.tipos') ? 'active' : '' }}">
                    <span class="nav-icon">🏷️</span> Tipos Actividad
                </a>
                <a href="{{ route('admin.tipo-gastronomico') }}" class="nav-item {{ request()->routeIs('admin.tipo-gastronomico') ? 'active' : '' }}">
                    <span class="nav-icon">🍴</span> Tipos Gastronóm.
                </a>
                <a href="{{ route('admin.menus') }}" class="nav-item {{ request()->routeIs('admin.menus') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span> Menús
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-label">Usuarios</div>
                <a href="{{ route('admin.usuarios') }}" class="nav-item {{ request()->routeIs('admin.usuarios') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span> Usuarios
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            @php $adminUser = session('admin_user'); @endphp
            <div class="admin-info">
                <div class="admin-avatar">{{ strtoupper(substr($adminUser['name'] ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="admin-name">{{ $adminUser['name'] ?? 'Admin' }}</div>
                    <div class="admin-role">Administrador</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <span>⎋</span> Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="main">
        <div class="topbar">
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>

@livewireScripts
</body>
</html>
