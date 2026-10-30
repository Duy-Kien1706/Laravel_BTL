<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản Lý Thuốc') - PharmaCare</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        /* Layout Wrapper */
        .admin-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        /* Left Sidebar */
        .sidebar {
            width: 260px;
            background: #0f172a;
            color: #e2e8f0;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
        }
        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #fff;
        }
        .sidebar-brand-icon {
            width: 38px;
            height: 38px;
            background: #2563eb;
            color: #fff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
        }
        .sidebar-brand-text h6 {
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }
        .sidebar-brand-text small {
            color: #94a3b8;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }
        .nav-category {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 12px 12px 6px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 14px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            margin-bottom: 4px;
        }
        .sidebar-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }
        .sidebar-link.active {
            color: #fff;
            background: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            font-weight: 600;
        }
        .sidebar-link i {
            font-size: 1.1rem;
        }
        .sidebar-user {
            padding: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.2);
        }
        /* Right Content Area */
        .admin-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
        }
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .content-body {
            flex: 1;
            padding: 24px 28px;
        }
        .footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 16px 28px;
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
        }
        /* Components */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
        }
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }
        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .badge {
            font-weight: 600;
            padding: 0.4em 0.7em;
            border-radius: 0.375rem;
        }
        .table > :not(caption) > * > * {
            padding: 0.85rem 0.75rem;
            vertical-align: middle;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.5rem;
        }
        @media (max-width: 992px) {
            .admin-layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
            }
        }
        @media print {
            .sidebar, .top-navbar, .footer, .btn, .alert, .no-print {
                display: none !important;
            }
            .admin-layout, .admin-main, .content-body {
                padding: 0 !important;
                margin: 0 !important;
                background: #fff !important;
                width: 100% !important;
                display: block !important;
            }
            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    @auth
        <div class="admin-layout">
            <!-- Left Sidebar Navigation -->
            <aside class="sidebar">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <div class="sidebar-brand-icon">
                        <i class="bi bi-capsule-pill"></i>
                    </div>
                    <div class="sidebar-brand-text">
                        <h6>PharmaCare</h6>
                        <small>Hệ Thống Quản Lý Kho</small>
                    </div>
                </a>

                <div class="sidebar-nav">
                    <div class="nav-category">Hệ Thống</div>
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Tổng Quan</span>
                    </a>

                    <div class="nav-category">Kho Dược Phẩm</div>
                    <a href="{{ route('medicines.index') }}" class="sidebar-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}">
                        <i class="bi bi-prescription2"></i>
                        <span>Quản Lý Thuốc</span>
                    </a>

                    <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span>Danh Mục Thuốc</span>
                    </a>

                    <a href="{{ route('warehouse.index') }}" class="sidebar-link {{ request()->routeIs('warehouse.*') ? 'active' : '' }}">
                        <i class="bi bi-boxes"></i>
                        <span>Quản Lý Kho</span>
                    </a>

                    <div class="nav-category">Bán Hàng & Nhân Sự</div>
                    <a href="{{ route('invoices.index') }}" class="sidebar-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i>
                        <span>Quản Lý Hóa Đơn</span>
                    </a>

                    <a href="{{ route('staff.index') }}" class="sidebar-link {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Quản Lý Nhân Viên</span>
                    </a>
                </div>

                <!-- User Profile & Logout at Bottom -->
                <div class="sidebar-user">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div style="line-height: 1.2;">
                                <div class="fw-semibold text-white small text-truncate" style="max-width: 170px;">{{ Auth::user()->name }}</div>
                                <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">{{ Auth::user()->role_name ?? 'Nhân viên' }}</span>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Đăng Xuất</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Right Main Content Area -->
            <div class="admin-main">
                <!-- Top Navbar -->
                <header class="top-navbar">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-secondary small">
                            <i class="bi bi-house-door me-1"></i> Trang Quản Trị &gt;
                            <strong class="text-dark">@yield('title', 'Hệ Thống')</strong>
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-success-subtle text-success-emphasis border px-3 py-2">
                            <i class="bi bi-shield-check me-1"></i> Hệ thống đang hoạt động
                        </span>
                        <div class="text-muted small d-none d-sm-block">
                            <i class="bi bi-calendar3 me-1"></i> {{ date('d/m/Y') }}
                        </div>
                    </div>
                </header>

                <!-- Content Body -->
                <main class="content-body">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                            <div>{{ session('error') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-1"></i> Vui lòng kiểm tra lại thông tin:</div>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="footer">
                    <div>&copy; {{ date('Y') }} <strong>PharmaCare</strong> - Quản Lý Kho Dược Phẩm </div>
                </footer>
            </div>
        </div>
    @else
        <!-- Guest View (Login) -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-2">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('login') }}">
                    <span class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                        <i class="bi bi-capsule-pill fs-5"></i>
                    </span>
                    <span>PharmaCare <small class="fw-normal fs-6 opacity-75">| Quản Lý Hiệu Thuốc</small></span>
                </a>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <span class="badge bg-white text-primary px-3 py-2 fw-semibold">
                        <i class="bi bi-shield-lock me-1"></i> Cổng Đăng Nhập Hệ Thống
                    </span>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    @endauth

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
