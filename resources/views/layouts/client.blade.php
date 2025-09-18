<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Client Dashboard - Denip Investments Ltd')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --primary: #2c3e50;
            --secondary: #f39c12;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #34495e;
            --white: #ffffff;
            --shadow: rgba(0,0,0,0.1);
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: var(--dark);
        }

        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary) 0%, var(--dark) 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .sidebar-subtitle {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .sidebar-close {
            display: none;
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .sidebar-close:hover {
            background: rgba(255,255,255,0.1);
        }

        .notification-badge {
            background: var(--accent);
            color: white;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 600;
            min-width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar .notification-badge {
            margin-left: 0.5rem;
            position: static;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background 0.3s ease;
        }

        .notification-icon:hover {
            background: var(--light);
        }

        .notification-icon i {
            font-size: 1.2rem;
            color: var(--dark);
        }

        .notification-icon .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            width: 350px;
            margin-top: 0.5rem;
            display: none;
            z-index: 1001;
            max-height: 400px;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .notification-dropdown {
                position: fixed;
                top: 60px;
                right: 10px;
                left: 10px;
                width: auto;
                max-height: 70vh;
            }
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid var(--light);
        }

        .notification-header h4 {
            margin: 0;
            color: var(--primary);
            font-size: 1rem;
        }

        .clear-btn {
            background: none;
            border: none;
            color: var(--accent);
            font-size: 0.8rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .clear-btn:hover {
            background: #fee2e2;
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .notification-item.unread {
            background: #fffbf0;
            border-left: 3px solid var(--secondary);
        }

        .notification-content {
            font-size: 0.9rem;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .notification-footer {
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--light);
            text-align: center;
        }

        .notification-footer a {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .loading-notifications {
            padding: 2rem;
            text-align: center;
            color: #6c757d;
        }

        .empty-notifications {
            padding: 2rem;
            text-align: center;
            color: #6c757d;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-section {
            margin-bottom: 2rem;
        }

        .nav-section-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.6;
            font-weight: 600;
        }

        .nav-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: var(--secondary);
        }

        .nav-item.active {
            background: rgba(243,156,18,0.2);
            color: white;
            border-left-color: var(--secondary);
        }

        .nav-item i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--dark);
            cursor: pointer;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            white-space: nowrap;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
            color: white;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px var(--shadow);
            min-width: 200px;
            margin-top: 0.5rem;
            display: none;
            z-index: 1001;
        }

        .user-dropdown a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--dark);
            text-decoration: none;
            border-bottom: 1px solid var(--light);
        }

        .user-dropdown a:hover { background: var(--light); }

        .content-area {
            padding: 2rem;
        }

        .dashboard-header {
            margin-bottom: 2rem;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px var(--shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--secondary), #e67e22);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .stat-content h3 {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .dashboard-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px var(--shadow);
            padding: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            color: var(--primary);
            font-size: 1.25rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary), #e67e22);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .btn:hover { transform: translateY(-2px); }

        .project-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--light);
        }

        .project-info h4 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active { background: #d4edda; color: #155724; }
        .status-planning { background: #fff3cd; color: #856404; }
        .status-completed { background: #cce5ff; color: #004085; }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .quick-actions {
            display: grid;
            gap: 1rem;
        }

        .action-card {
            padding: 1.5rem;
            border: 2px solid var(--light);
            border-radius: 12px;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s ease;
        }

        .action-card:hover {
            border-color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--shadow);
        }

        .action-card i {
            font-size: 2rem;
            color: var(--secondary);
            margin-bottom: 1rem;
        }

        .action-card h4 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .sidebar-close {
                display: block;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .content-area {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <svg viewBox="0 0 1605 502" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 24px; margin-right: 0.5rem; vertical-align: middle;">
                        <g transform="matrix(1,0,0,1,-698.678,-1249.19)">
                            <g transform="matrix(21.3645,0,0,21.3645,-449.868,-765.599)">
                                <g transform="matrix(0.818597,0,0,0.818597,15.8776,15.9463)">
                                    <path d="M46.277,108.659L68.821,108.617L68.821,103.451L50.495,103.451L46.277,108.659Z" fill="currentColor"/>
                                    <path d="M52.076,100.89L78.037,100.89L78.015,111.2L68.778,111.2L68.778,116.366L83.05,116.366L83.05,100.955L77.818,95.724L56.607,95.724L52.076,100.89Z" fill="currentColor"/>
                                    <path d="M56.979,111.178L66.019,111.178L66.019,116.235L53.145,116.235L56.979,111.178Z" fill="currentColor"/>
                                    <path d="M98.009,100.966L95.199,104.371L89.697,104.371L89.697,106.94L97.215,106.94L94.458,110.222L89.759,110.222L89.759,112.745L97.978,112.745L95.139,116.366L85.595,116.336L85.595,100.95L98.009,100.966Z" fill="currentColor"/>
                                    <path d="M100.408,116.344L100.408,100.89L104.173,100.89L110.893,109.471L110.893,100.89L115.118,100.89L115.118,116.333L111.233,116.333L104.403,108.156L104.403,116.351L100.408,116.344Z" fill="currentColor"/>
                                    <path d="M118.049,116.332L118.03,116.351L118.038,100.89L122.186,100.89L122.186,111.039L118.049,116.332Z" fill="currentColor"/>
                                    <path d="M125.096,100.89L132.805,100.89C132.805,100.89 138.022,100.668 138.029,106.227C138.035,111.421 133.548,111.847 133.548,111.847L129.399,111.847L129.399,116.351L125.096,116.351L125.096,100.89ZM132.127,109.129C133.845,109.129 134.839,108.021 134.839,106.303C134.839,104.585 133.845,103.476 132.127,103.476L129.367,103.476L129.367,109.129L132.127,109.129Z" fill="currentColor"/>
                                </g>
                            </g>
                        </g>
                    </svg>
                    DENIP INVESTMENTS
                </div>
                <div class="sidebar-subtitle">Client Portal</div>
                <button class="sidebar-close" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Projects</div>
                    <a href="{{ route('client.projects.index') }}" class="nav-item {{ request()->routeIs('client.projects.*') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram"></i>
                        My Projects
                    </a>
                    <a href="{{ route('client.proposals.index') }}" class="nav-item {{ request()->routeIs('client.proposals.*') ? 'active' : '' }}">
                        <i class="fas fa-handshake"></i>
                        Proposals
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Financial</div>
                    <a href="{{ route('client.invoices.index') }}" class="nav-item {{ request()->routeIs('client.invoices.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        Invoices
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <a href="{{ route('client.activities.index') }}" class="nav-item {{ request()->routeIs('client.activities.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        Activity Log
                    </a>
                    <a href="{{ route('client.messages.index') }}" class="nav-item {{ request()->routeIs('client.messages.*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        Messages
                        <span id="unread-count" class="notification-badge" style="display: none;"></span>
                    </a>
                    <a href="{{ route('client.calendar.index') }}" class="nav-item {{ request()->routeIs('client.calendar.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar"></i>
                        Calendar
                    </a>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Account</div>
                    <a href="{{ route('client.profile') }}" class="nav-item {{ request()->routeIs('client.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        Profile
                        @if(auth()->user()->mfa_enabled)
                            <i class="fas fa-shield-alt" style="color: var(--success); margin-left: 0.5rem; font-size: 0.8rem;" title="MFA Enabled"></i>
                        @endif
                    </a>
                    <a href="{{ route('landing.index') }}" class="nav-item">
                        <i class="fas fa-globe"></i>
                        Public Site
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="topbar-right">
                    <div class="notification-icon" onclick="toggleNotifications()" style="position: relative; cursor: pointer; padding: 0.5rem; border-radius: 50%; transition: background 0.3s ease; margin-right: 1rem;">
                        <i class="fas fa-bell" style="font-size: 1.2rem; color: var(--dark);"></i>
                        <span id="notification-count" class="notification-badge" style="position: absolute; top: 0; right: 0; background: var(--accent); color: white; border-radius: 50%; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 600; min-width: 18px; height: 18px; display: none;"></span>
                        <div id="notificationDropdown" class="notification-dropdown">
                            <div class="notification-header">
                                <h4>Notifications</h4>
                                <button onclick="clearNotifications()" class="clear-btn">Clear All</button>
                            </div>
                            <div id="notification-list" class="notification-list">
                                <div class="loading-notifications">Loading...</div>
                            </div>
                            <div class="notification-footer">
                                <a href="{{ route('client.messages.index') }}">View All Messages</a>
                            </div>
                        </div>
                    </div>
                    <div class="user-info">
                        <div class="user-avatar" onclick="toggleUserMenu()">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                {{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}
                            @endif
                        </div>
                        <div id="userDropdown" class="user-dropdown">
                            <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--light); font-weight: 600; color: var(--primary);">
                                {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                            </div>
                            <a href="{{ route('client.profile') }}">
                                <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                                Profile
                            </a>
                            <a href="{{ route('client.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Global Modals -->
    <x-modal id="notification-modal" title="Notification" size="default">
        <div id="notification-content"></div>
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="button" onclick="closeModal('notification-modal')" class="btn btn-primary" style="flex: 1;">OK</button>
        </div>
    </x-modal>

    <!-- MFA Setup Modal -->
    <x-modal id="mfa-setup-modal" title="Enable Two-Factor Authentication" size="default">
        <div id="mfaSetupContent">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="width: 80px; height: 80px; background: var(--light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: var(--secondary);">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h4 style="color: var(--primary); margin-bottom: 0.5rem;">Scan QR Code</h4>
                <p style="color: var(--dark); font-size: 0.9rem;">
                    Use your authenticator app to scan this QR code
                </p>
            </div>

            <div id="qrCodeContainer" style="text-align: center; margin: 1.5rem 0;">
                <!-- QR Code will be inserted here -->
            </div>

            <div style="background: var(--light); padding: 1rem; border-radius: 8px; margin: 1rem 0;">
                <p style="font-size: 0.875rem; color: var(--dark); margin: 0;">
                    <strong>Manual Entry:</strong> If you can't scan the QR code, enter this secret key manually:
                </p>
                <code id="secretKey" style="display: block; margin-top: 0.5rem; padding: 0.5rem; background: white; border-radius: 4px; font-family: monospace; word-break: break-all;"></code>
            </div>

            <form id="mfaConfirmForm" onsubmit="confirmMfa(event)">
                <x-form-field 
                    label="Verification Code" 
                    name="mfa_code" 
                    type="text" 
                    placeholder="000000"
                    :required="true"
                />
                
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <span class="btn-text">Verify & Enable</span>
                    </button>
                    <button type="button" onclick="closeModal('mfa-setup-modal')" class="btn btn-outline" style="flex: 1;">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Confirmation Modal -->
    <x-modal id="confirmation-modal" title="Confirm Action" size="default">
        <div id="confirmation-content"></div>
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="button" id="confirmBtn" class="btn" style="background: var(--accent); color: white; flex: 1;">Confirm</button>
            <button type="button" onclick="closeModal('confirmation-modal')" class="btn btn-outline" style="flex: 1;">Cancel</button>
        </div>
    </x-modal>

    <!-- MFA Disable Modal -->
    <x-modal id="mfa-disable-modal" title="Disable Two-Factor Authentication" size="default">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; color: var(--accent);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <p style="color: var(--dark);">
                This will reduce your account security. Please confirm by entering your password and current MFA code.
            </p>
        </div>

        <form id="mfaDisableForm" onsubmit="confirmDisableMfa(event)">
            <x-form-field 
                label="Current Password" 
                name="password" 
                type="password" 
                :required="true"
            />
            
            <x-form-field 
                label="MFA Code" 
                name="mfa_code" 
                type="text" 
                placeholder="000000"
                :required="true"
            />
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn" style="background: var(--accent); color: white; flex: 1;">
                    <span class="btn-text">Disable MFA</span>
                </button>
                <button type="button" onclick="closeModal('mfa-disable-modal')" class="btn btn-outline" style="flex: 1;">
                    Cancel
                </button>
            </div>
        </form>
    </x-modal>

    <script>
        // Global functions
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
        
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                background: ${type === 'success' ? '#28a745' : type === 'error' ? 'var(--accent)' : 'var(--secondary)'};
                color: white;
                border-radius: 8px;
                z-index: 10000;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                transform: translateX(100%);
                transition: transform 0.3s ease;
                font-weight: 600;
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // MFA Functions
        async function enableMfa() {
            try {
                const response = await fetch('/mfa/enable', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('qrCodeContainer').innerHTML = `<img src="${result.qr_code}" alt="QR Code" style="max-width: 200px;">`;
                    document.getElementById('secretKey').textContent = result.secret;
                    openModal('mfa-setup-modal');
                }
            } catch (error) {
                showNotification('Failed to generate MFA setup', 'error');
            }
        }

        async function confirmMfa(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            data.secret = document.getElementById('secretKey').textContent;
            data.code = data.mfa_code;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            setLoading(submitBtn, true, 'Verifying...');
            
            try {
                const response = await fetch('/mfa/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeModal('mfa-setup-modal');
                    showNotification('MFA enabled successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification('Invalid verification code', 'error');
                }
            } catch (error) {
                showNotification('Verification failed', 'error');
            } finally {
                setLoading(submitBtn, false, 'Verify & Enable');
            }
        }

        function disableMfa() {
            openModal('mfa-disable-modal');
        }

        async function confirmDisableMfa(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            const submitBtn = form.querySelector('button[type="submit"]');
            
            setLoading(submitBtn, true, 'Disabling...');
            
            try {
                const response = await fetch('/mfa/disable', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    closeModal('mfa-disable-modal');
                    showNotification('MFA disabled successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (result.errors.password) {
                        showNotification('Invalid password', 'error');
                    } else if (result.errors.mfa_code) {
                        showNotification('Invalid MFA code', 'error');
                    }
                }
            } catch (error) {
                showNotification('Failed to disable MFA', 'error');
            } finally {
                setLoading(submitBtn, false, 'Disable MFA');
            }
        }

        function setLoading(button, loading, text) {
            const btnText = button.querySelector('.btn-text');
            if (loading) {
                button.disabled = true;
                btnText.innerHTML = `<div class="spinner"></div> ${text}`;
            } else {
                button.disabled = false;
                btnText.textContent = text;
            }
        }

        function showConfirmation(title, message, onConfirm) {
            document.querySelector('#confirmation-modal h3').textContent = title;
            document.getElementById('confirmation-content').innerHTML = `<p>${message}</p>`;
            document.getElementById('confirmBtn').onclick = () => {
                closeModal('confirmation-modal');
                onConfirm();
            };
            openModal('confirmation-modal');
        }

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            const isVisible = dropdown.style.display === 'block';
            
            if (isVisible) {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'block';
                loadNotifications();
            }
        }

        async function loadNotifications() {
            const list = document.getElementById('notification-list');
            list.innerHTML = '<div class="loading-notifications">Loading...</div>';
            
            try {
                const response = await fetch('/client/notifications');
                const data = await response.json();
                
                if (data.notifications && data.notifications.length > 0) {
                    list.innerHTML = data.notifications.map(notification => `
                        <div class="notification-item ${notification.read_at ? '' : 'unread'}" onclick="markAsRead(${notification.id})">
                            <div class="notification-content">${notification.content}</div>
                            <div class="notification-time">${notification.time}</div>
                        </div>
                    `).join('');
                    
                    // Count unread notifications and update badge
                    const unreadCount = data.notifications.filter(n => !n.read_at).length;
                    const notificationBadge = document.getElementById('notification-count');
                    if (unreadCount > 0) {
                        notificationBadge.textContent = unreadCount;
                        notificationBadge.style.display = 'inline-flex';
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                } else {
                    list.innerHTML = '<div class="empty-notifications">No notifications</div>';
                    document.getElementById('notification-count').style.display = 'none';
                }
            } catch (error) {
                list.innerHTML = '<div class="empty-notifications">Failed to load notifications</div>';
            }
        }

        async function clearNotifications() {
            try {
                const response = await fetch('/client/notifications/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    document.getElementById('notification-list').innerHTML = '<div class="empty-notifications">No notifications</div>';
                    document.getElementById('notification-count').style.display = 'none';
                    const sidebarBadge = document.getElementById('unread-count');
                    if (sidebarBadge) sidebarBadge.style.display = 'none';
                    checkUnreadMessages();
                    showNotification('Notifications cleared', 'success');
                } else {
                    showNotification('Failed to clear notifications', 'error');
                }
            } catch (error) {
                showNotification('Failed to clear notifications', 'error');
            }
        }

        async function markAsRead(notificationId) {
            try {
                await fetch(`/client/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                checkUnreadMessages();
            } catch (error) {
                console.log('Failed to mark notification as read');
            }
        }

        // Check for unread messages
        async function checkUnreadMessages() {
            try {
                const response = await fetch('/client/unread-count');
                const data = await response.json();
                
                const sidebarBadge = document.getElementById('unread-count');
                const topbarBadge = document.getElementById('notification-count');
                
                if (data.count > 0) {
                    if (sidebarBadge) {
                        sidebarBadge.textContent = data.count;
                        sidebarBadge.style.display = 'inline-flex';
                    }
                    if (topbarBadge) {
                        console.log('Setting badge count:', data.count);
                        topbarBadge.textContent = data.count;
                        topbarBadge.style.setProperty('display', 'inline-flex', 'important');
                        console.log('Badge display after setting:', topbarBadge.style.display);
                    }
                } else {
                    if (sidebarBadge) sidebarBadge.style.display = 'none';
                    if (topbarBadge) topbarBadge.style.display = 'none';
                }
            } catch (error) {
                console.log('Failed to check unread messages');
            }
        }

        // Check unread messages on page load and periodically
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(checkUnreadMessages, 1000); // Delay to ensure elements are loaded
        });
        
        // Check every 30 seconds
        setInterval(checkUnreadMessages, 30000);

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-info')) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown) dropdown.style.display = 'none';
            }
            
            if (!e.target.closest('.notification-icon')) {
                const notificationDropdown = document.getElementById('notificationDropdown');
                if (notificationDropdown) notificationDropdown.style.display = 'none';
            }
        });
    </script>
    @stack('scripts')
</body>
</html>