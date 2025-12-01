<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EMS') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
    <!-- Custom CSS -->
    <style>
        :root {
            --brand-color: #3b82f6;
            --brand-hover: #2563eb;
            --sidebar-width-expanded: 290px;
            --sidebar-width-collapsed: 90px;
            --navbar-height: 64px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
            color: #111827;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e5e7eb;
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 1.25rem;
        }

        .navbar-brand {
            color: #111827 !important;
            font-weight: 700;
            font-size: 1.125rem;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            height: calc(100vh - var(--navbar-height));
            width: var(--sidebar-width-expanded);
            background-color: #ffffff;
            border-right: 1px solid #e5e7eb;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 999;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        .sidebar.collapsed {
            width: var(--sidebar-width-collapsed);
        }

        .sidebar-content {
            padding: 1.5rem 1.25rem;
        }

        /* Menu Group Styles */
        .menu-group {
            margin-bottom: 2rem;
        }

        .menu-group-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
            padding-left: 0.75rem;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .menu-group-title {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .sidebar.collapsed:hover .menu-group-title {
            opacity: 1;
            height: auto;
            margin-bottom: 1rem;
            padding-left: 0.75rem;
        }

        /* Menu Items */
        .menu-items {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .menu-item {
            position: relative;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            border-radius: 0.5rem;
            text-decoration: none;
            color: #6b7280;
            font-size: 0.9375rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .menu-link:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .menu-link.active {
            background-color: #eff6ff;
            color: var(--brand-color);
        }

        .menu-link.active .menu-icon {
            color: var(--brand-color);
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            min-width: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            color: #9ca3af;
            transition: all 0.2s;
        }

        .menu-link:hover .menu-icon {
            color: #111827;
        }

        .menu-text {
            flex: 1;
            white-space: nowrap;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed:hover .menu-text {
            opacity: 1;
            width: auto;
        }

        .menu-arrow {
            width: 20px;
            height: 20px;
            margin-left: auto;
            transition: transform 0.2s, opacity 0.3s;
        }

        .menu-arrow.rotated {
            transform: rotate(180deg);
        }

        .sidebar.collapsed .menu-arrow {
            opacity: 0;
            width: 0;
        }

        .sidebar.collapsed:hover .menu-arrow {
            opacity: 1;
            width: 20px;
        }

        /* Submenu Styles */
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 0 2.5rem;
            display: none;
        }

        .submenu.show {
            display: block;
        }

        .submenu-item {
            margin-bottom: 0.25rem;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.75rem;
            border-radius: 0.375rem;
            text-decoration: none;
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .submenu-link:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        .submenu-link.active {
            background-color: #eff6ff;
            color: var(--brand-color);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width-expanded);
            margin-top: var(--navbar-height);
            padding: 1.5rem;
            min-height: calc(100vh - var(--navbar-height));
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.expanded {
            margin-left: var(--sidebar-width-collapsed);
        }

        /* Mobile Styles */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                top: 0;
                height: 100vh;
                z-index: 1050;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1049;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .sidebar .menu-text,
            .sidebar .menu-arrow,
            .sidebar .menu-group-title {
                opacity: 1 !important;
                width: auto !important;
                height: auto !important;
                margin: initial !important;
                padding: initial !important;
            }
        }

        /* Toggle Button */
        .sidebar-toggle {
            background: none;
            border: none;
            color: #6b7280;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .sidebar-toggle:hover {
            background-color: #f3f4f6;
            color: #111827;
        }

        /* User Dropdown */
        .user-dropdown .dropdown-toggle {
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        .user-dropdown .dropdown-toggle:hover {
            background-color: #f9fafb;
        }

        /* Utility Classes */
        .d-lg-none {
            display: none !important;
        }

        @media (max-width: 991px) {
            .d-lg-none {
                display: block !important;
            }

            .d-lg-block {
                display: none !important;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="sidebar-toggle d-lg-block me-2" id="sidebarToggle" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <button class="sidebar-toggle d-lg-none" type="button" id="mobileSidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand ms-2"
                    href="{{ Auth::check() ? (Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isClient() ? route('client.dashboard') : route('employee.dashboard'))) : url('/') }}">
                    EMS
                </a>
                <div class="ms-auto">
                    @auth
                        <div class="dropdown user-dropdown">
                            <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"
                                onsubmit="return checkLogoutSubmission()">
                                @csrf
                            </form>

                            <script>
                                // Prevent multiple logout submissions
                                let logoutSubmitted = false;

                                function checkLogoutSubmission() {
                                    if (logoutSubmitted) {
                                        return false;
                                    }
                                    logoutSubmitted = true;
                                    return true;
                                }

                                document.addEventListener('DOMContentLoaded', function() {
                                    const logoutForm = document.getElementById('logout-form');
                                    const logoutLinks = document.querySelectorAll('a[href*="logout"], a[onclick*="logout-form"]');

                                    logoutLinks.forEach(link => {
                                        link.addEventListener('click', function(e) {
                                            e.preventDefault();

                                            if (logoutSubmitted || this.disabled) {
                                                return false;
                                            }

                                            logoutSubmitted = true;
                                            this.disabled = true;

                                            logoutForm.submit();
                                        });
                                    });
                                });
                            </script>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        @auth
            <!-- Sidebar -->
            <aside class="sidebar" id="sidebarMenu">
                <div class="sidebar-content">
                    <!-- Admin Menu -->
                    @if (Auth::user()->isAdmin())
                        <div class="menu-group">
                            <h2 class="menu-group-title">Menu</h2>
                            <ul class="menu-items">
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                        href="{{ route('admin.dashboard') }}">
                                        <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                                        <span class="menu-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('admin.employees*') ? 'active' : '' }}"
                                        href="{{ route('admin.employees.index') }}">
                                        <span class="menu-icon"><i class="fas fa-user-tie"></i></span>
                                        <span class="menu-text">Employees</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('admin.clients*') ? 'active' : '' }}"
                                        href="{{ route('admin.clients.index') }}">
                                        <span class="menu-icon"><i class="fas fa-user-friends"></i></span>
                                        <span class="menu-text">Clients</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('admin.shifts*') ? 'active' : '' }}"
                                        href="{{ route('admin.shifts.index') }}">
                                        <span class="menu-icon"><i class="fas fa-clock"></i></span>
                                        <span class="menu-text">Shifts</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('admin.employee-attendance*') ? 'active' : '' }}"
                                        href="{{ route('admin.employee-attendance.index') }}">
                                        <span class="menu-icon"><i class="fas fa-calendar-check"></i></span>
                                        <span class="menu-text">Employee Attendance</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('admin.client-attendance*') ? 'active' : '' }}"
                                        href="{{ route('admin.client-attendance.index') }}">
                                        <span class="menu-icon"><i class="fas fa-calendar-check"></i></span>
                                        <span class="menu-text">Client Attendance</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('admin.payroll*') ? 'active' : '' }}"
                                        href="{{ route('admin.payroll.index') }}">
                                        <span class="menu-icon"><i class="fas fa-dollar-sign"></i></span>
                                        <span class="menu-text">Payroll</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- <div class="menu-group">
                        <h2 class="menu-group-title">Others</h2>
                        <ul class="menu-items">
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                                    <span class="menu-icon"><i class="fas fa-chart-bar"></i></span>
                                    <span class="menu-text">Reports</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                    <span class="menu-icon"><i class="fas fa-cog"></i></span>
                                    <span class="menu-text">Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div> --}}

                        <div class="menu-group">
                            <h2 class="menu-group-title">Account</h2>
                            <ul class="menu-items">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                                        <span class="menu-text">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @elseif(Auth::user()->isClient())
                        <!-- Client Menu -->
                        <div class="menu-group">
                            <h2 class="menu-group-title">Menu</h2>
                            <ul class="menu-items">
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}"
                                        href="{{ route('client.dashboard') }}">
                                        <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                                        <span class="menu-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('client.shifts*') ? 'active' : '' }}"
                                        href="{{ route('client.shifts.index') }}">
                                        <span class="menu-icon"><i class="fas fa-clock"></i></span>
                                        <span class="menu-text">My Shifts</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('client.attendance*') ? 'active' : '' }}"
                                        href="{{ route('client.attendance.index') }}">
                                        <span class="menu-icon"><i class="fas fa-calendar-check"></i></span>
                                        <span class="menu-text">Attendance</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('client.payroll*') ? 'active' : '' }}"
                                        href="{{ route('client.payroll.index') }}">
                                        <span class="menu-icon"><i class="fas fa-dollar-sign"></i></span>
                                        <span class="menu-text">Payroll</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="menu-group">
                            <h2 class="menu-group-title">Others</h2>
                            <ul class="menu-items">
                                {{-- <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('client.requests*') ? 'active' : '' }}" href="{{ route('client.requests.index') }}">
                                    <span class="menu-icon"><i class="fas fa-exchange-alt"></i></span>
                                    <span class="menu-text">Shift Requests</span>
                                </a>
                            </li> --}}
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('client.profile*') ? 'active' : '' }}"
                                        href="{{ route('client.profile.edit') }}">
                                        <span class="menu-icon"><i class="fas fa-user"></i></span>
                                        <span class="menu-text">Profile</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="menu-group">
                            <h2 class="menu-group-title">Account</h2>
                            <ul class="menu-items">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                                        <span class="menu-text">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Employee Menu -->
                        <div class="menu-group">
                            <h2 class="menu-group-title">Menu</h2>
                            <ul class="menu-items">
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}"
                                        href="{{ route('employee.dashboard') }}">
                                        <span class="menu-icon"><i class="fas fa-tachometer-alt"></i></span>
                                        <span class="menu-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('employee.shifts*') ? 'active' : '' }}"
                                        href="{{ route('employee.shifts.index') }}">
                                        <span class="menu-icon"><i class="fas fa-clock"></i></span>
                                        <span class="menu-text">My Shifts</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('employee.attendance*') ? 'active' : '' }}"
                                        href="{{ route('employee.attendance.index') }}">
                                        <span class="menu-icon"><i class="fas fa-calendar-check"></i></span>
                                        <span class="menu-text">Attendance</span>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('employee.payroll*') ? 'active' : '' }}"
                                        href="{{ route('employee.payroll.index') }}">
                                        <span class="menu-icon"><i class="fas fa-dollar-sign"></i></span>
                                        <span class="menu-text">Payroll</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="menu-group">
                            <h2 class="menu-group-title">Others</h2>
                            <ul class="menu-items">
                                {{-- <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('employee.requests*') ? 'active' : '' }}" href="{{ route('employee.requests.index') }}">
                                    <span class="menu-icon"><i class="fas fa-exchange-alt"></i></span>
                                    <span class="menu-text">Shift Requests</span>
                                </a>
                            </li> --}}
                                <li class="menu-item">
                                    <a class="menu-link {{ request()->routeIs('employee.profile*') ? 'active' : '' }}"
                                        href="{{ route('employee.profile.edit') }}">
                                        <span class="menu-icon"><i class="fas fa-user"></i></span>
                                        <span class="menu-text">Profile</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="menu-group">
                            <h2 class="menu-group-title">Account</h2>
                            <ul class="menu-items">
                                <li class="menu-item">
                                    <a class="menu-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <span class="menu-icon"><i class="fas fa-sign-out-alt"></i></span>
                                        <span class="menu-text">Logout</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </aside>
        @endauth

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert"
                        style="margin-bottom: 1rem;">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert"
                        style="margin-bottom: 1rem;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteMessage">Are you sure you want to delete this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables for delete functionality
        let deleteUrl = '';
        let csrfToken = '{{ csrf_token() }}';

        // Button disabling functionality
        function disableButton(button, loadingText = 'Loading...') {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${loadingText}`;
        }

        function enableButton(button) {
            button.disabled = false;
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
            }
        }

        // Disable buttons on form submission and AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            // Handle form submissions (only for non-login forms)
            document.querySelectorAll('form').forEach(form => {
                if (!form.action.includes('/login')) {
                    form.addEventListener('submit', function(e) {
                        const submitButton = form.querySelector(
                            'button[type="submit"], input[type="submit"]');
                        if (submitButton && !submitButton.disabled) {
                            disableButton(submitButton, 'Submitting...');
                        }
                    });
                }
            });



            // Handle AJAX requests for accept/reject shift buttons
            document.querySelectorAll('.accept-shift, .reject-shift').forEach(button => {
                button.addEventListener('click', function() {
                    if (!this.disabled) {
                        disableButton(this, 'Processing...');
                    }
                });
            });

            // Re-enable buttons on AJAX errors
            document.addEventListener('ajaxError', function(e) {
                // Re-enable all disabled buttons on error
                document.querySelectorAll('button:disabled, a:disabled').forEach(button => {
                    enableButton(button);
                });
            });

            // Re-enable buttons on page navigation (back/forward)
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    // Page was restored from cache, re-enable buttons
                    document.querySelectorAll('button:disabled, a:disabled').forEach(button => {
                        enableButton(button);
                    });
                }
            });
        });

        // Delete functions
        function deleteEmployee(id, name) {
            deleteUrl = '{{ route('admin.employees.index') }}/' + id;
            document.getElementById('deleteMessage').textContent =
                `Are you sure you want to delete employee "${name}"? This action cannot be undone.`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function deleteShift(id, name) {
            deleteUrl = '{{ route('admin.shifts.index') }}/' + id;
            document.getElementById('deleteMessage').textContent =
                `Are you sure you want to delete shift "${name}"? This action cannot be undone.`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function deleteAttendance(id, description) {
            deleteUrl = '{{ route('admin.attendance.index') }}/' + id;
            document.getElementById('deleteMessage').textContent =
                `Are you sure you want to delete attendance record "${description}"? This action cannot be undone.`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Handle delete confirmation
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (deleteUrl) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        });

        // Show message function
        function showMessage(message, type = 'success') {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
            alertDiv.style.cssText =
                'margin-bottom: 1rem; position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="fas ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }

        // Sidebar functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const sidebar = document.getElementById('sidebarMenu');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('sidebarOverlay');

            // Desktop sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                });
            }

            // Mobile sidebar toggle
            if (mobileSidebarToggle && sidebar && overlay) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            // Close mobile sidebar on nav link click
            if (sidebar) {
                const navLinks = sidebar.querySelectorAll('.menu-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 992) {
                            sidebar.classList.remove('show');
                            overlay.classList.remove('show');
                        }
                    });
                });
            }

            // Submenu toggle functionality
            const menuButtons = document.querySelectorAll('.menu-link[data-bs-toggle="collapse"]');
            menuButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const arrow = this.querySelector('.menu-arrow');
                    if (arrow) {
                        arrow.classList.toggle('rotated');
                    }
                });
            });
        });
    </script>
</body>

</html>
