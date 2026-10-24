<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản Lý Thuốc') - PharmaCare (Ant Design)</title>
    <!-- Ant Design CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/antd/4.24.16/antd.min.css">
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --ant-primary: #1677ff;
            --ant-primary-hover: #4096ff;
            --ant-success: #52c41a;
            --ant-warning: #faad14;
            --ant-danger: #ff4d4f;
            --ant-bg: #f5f5f5;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--ant-bg);
            margin: 0;
            padding: 0;
            color: rgba(0, 0, 0, 0.85);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .ant-layout {
            background: transparent;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .ant-layout-header {
            background: #001529;
            height: 64px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        .header-brand .brand-icon {
            width: 32px;
            height: 32px;
            background: #1677ff;
            color: #fff;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }
        .header-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 32px;
        }
        .header-menu a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .header-menu a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        .header-menu a.active {
            color: #fff;
            background: #1677ff;
            font-weight: 500;
        }
        .header-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .ant-layout-content {
            flex: 1;
            padding: 24px 32px;
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }
        .ant-layout-footer {
            background: #fff;
            padding: 16px 32px;
            text-align: center;
            color: rgba(0, 0, 0, 0.45);
            font-size: 13px;
            border-top: 1px solid #f0f0f0;
        }
        /* Custom Helper Classes */
        .ant-card {
            border-radius: 8px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03), 0 1px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px 0 rgba(0, 0, 0, 0.02);
            border: 1px solid #f0f0f0;
        }
        .ant-grid-row {
            display: flex;
            flex-wrap: wrap;
            margin: -8px;
        }
        .ant-col-6 {
            flex: 0 0 25%;
            max-width: 25%;
            padding: 8px;
            box-sizing: border-box;
        }
        .ant-col-12 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 8px;
            box-sizing: border-box;
        }
        .ant-col-24 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        @media (max-width: 768px) {
            .ant-col-6, .ant-col-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .ant-layout-content {
                padding: 16px;
            }
            .header-menu {
                display: none;
            }
        }
        .ant-table table {
            width: 100%;
            text-align: left;
            border-collapse: separate;
            border-spacing: 0;
        }
        .ant-table th {
            background: #fafafa;
            color: rgba(0, 0, 0, 0.85);
            font-weight: 600;
            padding: 14px 16px;
            border-bottom: 1px solid #f0f0f0;
        }
        .ant-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
        }
        .ant-table tr:hover td {
            background: #fafafa;
        }
        .ant-form-item {
            margin-bottom: 20px;
        }
        .ant-form-item-label {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
            color: rgba(0, 0, 0, 0.85);
        }
        .ant-form-item-label.required::before {
            content: "*";
            color: #ff4d4f;
            margin-right: 4px;
            font-family: SimSun, sans-serif;
        }
        .ant-input, select.ant-input {
            width: 100%;
            height: 38px;
            padding: 4px 11px;
            border: 1px solid #d9d9d9;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s;
            box-sizing: border-box;
            background-color: #fff;
        }
        .ant-input:focus, select.ant-input:focus {
            border-color: #4096ff;
            box-shadow: 0 0 0 2px rgba(22, 119, 255, 0.2);
            outline: 0;
        }
        .ant-input-error {
            border-color: #ff4d4f !important;
        }
        .ant-input-error-msg {
            color: #ff4d4f;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }
        .ant-btn {
            border-radius: 6px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 36px;
            padding: 4px 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #d9d9d9;
            background: #fff;
            color: rgba(0, 0, 0, 0.85);
            text-decoration: none;
        }
        .ant-btn:hover {
            color: #4096ff;
            border-color: #4096ff;
        }
        .ant-btn-primary {
            background: #1677ff;
            border-color: #1677ff;
            color: #fff;
        }
        .ant-btn-primary:hover {
            background: #4096ff;
            border-color: #4096ff;
            color: #fff;
        }
        .ant-btn-danger {
            color: #ff4d4f;
            border-color: #ff4d4f;
        }
        .ant-btn-danger:hover {
            background: #ff4d4f;
            color: #fff;
        }
        .ant-btn-sm {
            height: 28px;
            padding: 2px 10px;
            font-size: 12px;
            border-radius: 4px;
        }
        .ant-tag {
            display: inline-block;
            padding: 1px 8px;
            font-size: 12px;
            line-height: 20px;
            border-radius: 4px;
            border: 1px solid #d9d9d9;
            background: #fafafa;
            color: rgba(0, 0, 0, 0.85);
        }
        .ant-tag-blue {
            color: #0958d9;
            background: #e6f4ff;
            border-color: #91caff;
        }
        .ant-tag-green {
            color: #389e0d;
            background: #f6ffed;
            border-color: #b7eb8f;
        }
        .ant-tag-orange {
            color: #d46b08;
            background: #fff7e6;
            border-color: #ffd591;
        }
        .ant-tag-red {
            color: #cf1322;
            background: #fff1f0;
            border-color: #ffa39e;
        }
        .ant-tag-purple {
            color: #531dab;
            background: #f9f0ff;
            border-color: #d3adf7;
        }
        .ant-alert {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            margin-bottom: 16px;
            border-radius: 8px;
            font-size: 14px;
            gap: 10px;
        }
        .ant-alert-success {
            background-color: #f6ffed;
            border: 1px solid #b7eb8f;
            color: rgba(0, 0, 0, 0.85);
        }
        .ant-alert-success i {
            color: #52c41a;
            font-size: 16px;
        }
        .ant-alert-error {
            background-color: #fff2f0;
            border: 1px solid #ffccc7;
            color: rgba(0, 0, 0, 0.85);
        }
        .ant-alert-error i {
            color: #ff4d4f;
            font-size: 16px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="ant-layout">
        <!-- Ant Design Header -->
        <header class="ant-layout-header">
            <div style="display: flex; align-items: center;">
                <a href="{{ route('medicines.index') }}" class="header-brand">
                    <div class="brand-icon">
                        <i class="fa-solid fa-capsules"></i>
                    </div>
                    <span>PharmaCare</span>
                </a>

                @auth
                    <nav class="header-menu">
                        <a href="{{ route('medicines.create') }}" class="{{ request()->routeIs('medicines.create') ? 'active' : '' }}">
                            <i class="fa-solid fa-plus-circle"></i> Thêm Thuốc Mới
                        </a>
                    </nav>
                @endauth
            </div>

            <div class="header-user">
                @auth
                    <span class="ant-tag ant-tag-blue" style="margin: 0; font-size: 13px; padding: 2px 10px;">
                        <i class="fa-solid fa-shield-halved" style="margin-right: 4px;"></i> {{ Auth::user()->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="ant-btn ant-btn-sm" style="background: rgba(255,255,255,0.15); color: #fff; border-color: transparent;" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng Xuất
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="ant-btn ant-btn-sm ant-btn-primary">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng Nhập
                    </a>
                    <a href="{{ route('register') }}" class="ant-btn ant-btn-sm" style="background: transparent; color: #fff; border-color: rgba(255,255,255,0.4);">
                        <i class="fa-solid fa-user-plus"></i> Đăng Ký
                    </a>
                @endauth
            </div>
        </header>

        <!-- Main Content -->
        <main class="ant-layout-content">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="ant-alert ant-alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="ant-alert ant-alert-error">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="ant-alert ant-alert-error" style="align-items: flex-start;">
                    <i class="fa-solid fa-circle-exclamation" style="margin-top: 2px;"></i>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 4px;">Vui lòng kiểm tra lại thông tin:</div>
                        <ul style="margin: 0; padding-left: 18px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Ant Design Footer -->
        <footer class="ant-layout-footer">
            <div>PharmaCare &copy; {{ date('Y') }} - Hệ Thống Quản Lý Kho Thuốc</div>
            <div style="margin-top: 4px; font-size: 12px; color: rgba(0,0,0,0.35);">
                Được xây dựng bởi nhóm số 10
            </div>
        </footer>
    </div>
    @yield('scripts')
</body>
</html>