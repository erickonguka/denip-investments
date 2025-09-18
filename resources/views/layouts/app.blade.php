<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Denip Investments</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/css/admin.css" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <div class="container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <svg viewBox="0 0 1605 502" xmlns="http://www.w3.org/2000/svg">
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
                    Denip Investments
                </div>
            </div>
            <div class="nav-menu">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <div class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Business</div>
                    <div class="nav-item">
                        <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            Clients
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            Categories
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                            <i class="fas fa-project-diagram"></i>
                            Projects
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') && !request()->routeIs('services') ? 'active' : '' }}">
                            <i class="fas fa-cogs"></i>
                            Services
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('team-members.index') }}" class="nav-link {{ request()->routeIs('team-members.*') ? 'active' : '' }}">
                            <i class="fas fa-user-friends"></i>
                            Team Members
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Financial</div>
                    <div class="nav-item">
                        <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice"></i>
                            Invoices
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('proposals.index') }}" class="nav-link {{ request()->routeIs('proposals.*') ? 'active' : '' }}">
                            <i class="fas fa-file-contract"></i>
                            Proposals
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('quotations.index') }}" class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
                            <i class="fas fa-calculator"></i>
                            Quotations
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">HR & Recruitment</div>
                    <div class="nav-item">
                        <a href="{{ route('careers.index') }}" class="nav-link {{ request()->routeIs('careers.*') ? 'active' : '' }}">
                            <i class="fas fa-briefcase"></i>
                            Careers
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Communication</div>
                    <div class="nav-item">
                        <a href="{{ route('admin.messages.index') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                            <i class="fas fa-comments"></i>
                            Messages
                            <span id="admin-unread-count" class="notification-badge" style="display: none; background: var(--error); color: white; border-radius: 50%; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 600; min-width: 18px; height: 18px; margin-left: 0.5rem; display: inline-flex; align-items: center; justify-content: center;"></span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.calendar.index') }}" class="nav-link {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar"></i>
                            Calendar
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('blogs.index') }}" class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}">
                            <i class="fas fa-blog"></i>
                            Blog Posts
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('blog-categories.index') }}" class="nav-link {{ request()->routeIs('blog-categories.*') ? 'active' : '' }}">
                            <i class="fas fa-folder-open"></i>
                            Blog Categories
                        </a>
                    </div>
                </div>
                
                <div class="nav-section">
                    <div class="nav-section-title">Administration</div>
                    <div class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-user-cog"></i>
                            User Management
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield"></i>
                            Roles & Permissions
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                            <i class="fas fa-history"></i>
                            User Activities
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Header -->
            <header class="header">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="header-actions">
                    <div class="notification-icon" onclick="toggleNotifications()" style="position: relative; cursor: pointer; padding: 0.5rem; border-radius: 50%; transition: background 0.3s ease; margin-right: 1rem;">
                        <i class="fas fa-bell" style="font-size: 1.2rem; color: var(--gray-600);"></i>
                        <span id="admin-notification-count" class="notification-badge" style="display: none; position: absolute; top: 0; right: 0; background: var(--error); color: white; border-radius: 50%; padding: 0.25rem 0.5rem; font-size: 0.7rem; font-weight: 600; min-width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center;"></span>
                        <div id="adminNotificationDropdown" class="notification-dropdown" style="display: none; position: absolute; top: 100%; right: 0; background: white; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); width: 350px; margin-top: 0.5rem; z-index: 1001; max-height: 400px; overflow: hidden;">
                            <div class="notification-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid var(--gray-100);">
                                <h4 style="margin: 0; color: var(--primary-blue); font-size: 1rem;">Notifications</h4>
                                <button onclick="clearAdminNotifications()" style="background: none; border: none; color: var(--error); font-size: 0.8rem; cursor: pointer; padding: 0.25rem 0.5rem; border-radius: 4px;">Clear All</button>
                            </div>
                            <div id="admin-notification-list" class="notification-list" style="max-height: 300px; overflow-y: auto;">
                                <div class="loading-notifications" style="padding: 2rem; text-align: center; color: #6c757d;">Loading...</div>
                            </div>
                            <div class="notification-footer" style="padding: 0.75rem 1rem; border-top: 1px solid var(--gray-100); text-align: center;">
                                <a href="{{ route('activities.index') }}" style="color: var(--primary-blue); text-decoration: none; font-size: 0.9rem;">View All Activities</a>
                            </div>
                        </div>
                    </div>
                    <div class="user-menu" style="position: relative;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;" onclick="toggleUserMenu()">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                            @endif
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div id="userDropdown" class="user-dropdown" style="display: none; right: 0;">
                            <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-100); font-weight: 600; color: var(--primary-blue);">
                                {{ auth()->user()->name }}
                            </div>
                            <a href="{{ route('landing.index') }}" style="display: block; padding: 0.75rem 1rem; color: var(--gray-700); text-decoration: none; border-bottom: 1px solid var(--gray-100);">
                                <i class="fas fa-home" style="margin-right: 0.5rem;"></i>
                                Public Site
                            </a>
                            <a href="#" onclick="openModal('account-modal')" style="display: block; padding: 0.75rem 1rem; color: var(--gray-700); text-decoration: none; border-bottom: 1px solid var(--gray-100);">
                                <i class="fas fa-user" style="margin-right: 0.5rem;"></i>
                                Account Settings
                            </a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="display: block; padding: 0.75rem 1rem; color: var(--error); text-decoration: none;">
                                <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </header>

            <!-- Content Area -->
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Account Settings Modal -->
    <x-auth-modal />
    
    <!-- Global Message Modal -->
    <x-message-modal />
    
    <!-- Global Delete Modal -->
    <x-delete-modal />

    <script src="/js/admin.js"></script>
    <script>
        function toggleNotifications() {
            const dropdown = document.getElementById('adminNotificationDropdown');
            const isVisible = dropdown.style.display === 'block';
            
            if (isVisible) {
                dropdown.style.display = 'none';
            } else {
                dropdown.style.display = 'block';
                loadAdminNotifications();
            }
        }

        async function loadAdminNotifications() {
            const list = document.getElementById('admin-notification-list');
            list.innerHTML = '<div class="loading-notifications" style="padding: 2rem; text-align: center; color: #6c757d;">Loading...</div>';
            
            try {
                const response = await fetch('/admin/notifications');
                const data = await response.json();
                
                if (data.notifications && data.notifications.length > 0) {
                    list.innerHTML = data.notifications.map(notification => `
                        <div class="notification-item" style="padding: 0.75rem 1rem; border-bottom: 1px solid #f8f9fa; cursor: pointer; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                            <div style="font-size: 0.9rem; color: var(--gray-700); margin-bottom: 0.25rem;">${notification.content}</div>
                            <div style="font-size: 0.75rem; color: #6c757d;">${notification.time}</div>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<div style="padding: 2rem; text-align: center; color: #6c757d;">No notifications</div>';
                }
            } catch (error) {
                list.innerHTML = '<div style="padding: 2rem; text-align: center; color: #6c757d;">Failed to load notifications</div>';
            }
        }

        async function clearAdminNotifications() {
            try {
                const response = await fetch('/admin/notifications/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    document.getElementById('admin-notification-list').innerHTML = '<div style="padding: 2rem; text-align: center; color: #6c757d;">No notifications</div>';
                    const topbarBadge = document.getElementById('admin-notification-count');
                    const sidebarBadge = document.getElementById('admin-unread-count');
                    if (topbarBadge) topbarBadge.style.display = 'none';
                    if (sidebarBadge) sidebarBadge.style.display = 'none';
                    checkAdminUnreadMessages();
                }
                
            } catch (error) {
                console.log('Failed to clear notifications');
            }
        }

        // Check for unread messages for admin
        async function checkAdminUnreadMessages() {
            try {
                const response = await fetch('/admin/unread-count');
                const data = await response.json();
                
                const topbarBadge = document.getElementById('admin-notification-count');
                const sidebarBadge = document.getElementById('admin-unread-count');
                
                if (data.count > 0) {
                    if (topbarBadge) {
                        topbarBadge.textContent = data.count;
                        topbarBadge.style.display = 'inline-flex';
                    }
                    if (sidebarBadge) {
                        sidebarBadge.textContent = data.count;
                        sidebarBadge.style.display = 'inline-flex';
                    }
                } else {
                    if (topbarBadge) topbarBadge.style.display = 'none';
                    if (sidebarBadge) sidebarBadge.style.display = 'none';
                }
            } catch (error) {
                console.log('Failed to check unread messages');
            }
        }

        // Check unread messages on page load and every 30 seconds
        document.addEventListener('DOMContentLoaded', checkAdminUnreadMessages);
        setInterval(checkAdminUnreadMessages, 30000);
    </script>
    <style>
        @media (max-width: 768px) {
            .notification-dropdown {
                position: fixed !important;
                top: 60px !important;
                right: 10px !important;
                left: 10px !important;
                width: auto !important;
                max-height: 70vh !important;
            }
        }
    </style>
    <script>

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.notification-icon')) {
                const dropdown = document.getElementById('adminNotificationDropdown');
                if (dropdown) dropdown.style.display = 'none';
            }
        });
    </script>
    @stack('scripts')
</body>
</html>