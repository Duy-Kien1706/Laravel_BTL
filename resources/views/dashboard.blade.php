@extends('layouts.app')

@section('title', 'Tổng Quan Hoạt Động')

@section('content')
<!-- Header & Quick Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-speedometer2 text-primary me-2"></i> Bảng Điều Khiển Tổng Quan</h3>
        <p class="text-muted small mb-0">Hệ thống tổng hợp số liệu doanh thu bán lẻ, tình hình kho vận và nhân sự</p>
    </div>
</div>

<!-- 4 Thẻ Tài Chính, Nhà Cung Cấp & Nhân Sự -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 bg-success-subtle text-success-emphasis shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold opacity-75">Doanh Thu Hôm Nay</div>
                    <div class="fs-4 fw-bold mt-1 text-success">{{ number_format($stats['today_revenue'], 0, ',', '.') }} đ</div>
                    <div class="small text-muted mt-1">Cập nhật theo ngày hiện tại</div>
                </div>
                <div class="stat-icon bg-success text-white shadow-sm">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 bg-primary-subtle text-primary-emphasis shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold opacity-75">Tổng Doanh Thu Bán</div>
                    <div class="fs-4 fw-bold mt-1 text-primary">{{ number_format($stats['total_revenue'], 0, ',', '.') }} đ</div>
                    <div class="small text-muted mt-1">{{ number_format($stats['total_invoices']) }} hóa đơn thanh toán</div>
                </div>
                <div class="stat-icon bg-primary text-white shadow-sm">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 bg-info-subtle text-info-emphasis shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold opacity-75">Nhà Cung Cấp Dược</div>
                    <div class="fs-4 fw-bold mt-1 text-info">{{ number_format($stats['total_suppliers']) }}</div>
                    <div class="small text-muted mt-1">Đối tác phân phối thuốc</div>
                </div>
                <div class="stat-icon bg-info text-white shadow-sm">
                    <i class="bi bi-truck"></i>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isAdmin())
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 bg-purple-subtle text-dark shadow-sm" style="background-color: #f3e8ff;">
                <div class="card-body d-flex align-items-center justify-content-between p-3">
                    <div>
                        <div class="text-uppercase small fw-bold opacity-75">Đội Ngũ Nhân Viên</div>
                        <div class="fs-4 fw-bold mt-1 text-indigo" style="color: #6b21a8;">{{ number_format($stats['total_staff']) }}</div>
                        <div class="small text-muted mt-1">Dược sĩ, thủ kho & admin</div>
                    </div>
                    <div class="stat-icon text-white shadow-sm" style="background-color: #7c3aed;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- 4 Thẻ Kho Dược & Cảnh Báo -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold text-secondary">Tổng Loại Thuốc</div>
                    <div class="fs-4 fw-bold mt-1 text-dark">{{ number_format($stats['total_medicines']) }}</div>
                    <div class="small text-muted">{{ number_format($stats['total_categories']) }} danh mục</div>
                </div>
                <div class="stat-icon bg-light text-primary border">
                    <i class="bi bi-capsule"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold text-secondary">Tổng Tồn Kho Thuốc</div>
                    <div class="fs-4 fw-bold mt-1 text-dark">{{ number_format($stats['total_stock']) }}</div>
                    <div class="small text-muted">Số đơn vị đang lưu trữ</div>
                </div>
                <div class="stat-icon bg-light text-success border">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold text-secondary">Sắp Hết Hàng (≤10)</div>
                    <div class="fs-4 fw-bold mt-1 {{ $stats['low_stock'] > 0 ? 'text-warning' : 'text-dark' }}">
                        {{ number_format($stats['low_stock']) }}
                    </div>
                    <div class="small text-muted">Cần nhập bổ sung kho</div>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold text-secondary">Hạn Dùng (≤30 ngày)</div>
                    <div class="fs-4 fw-bold mt-1 {{ $stats['expiring_soon'] > 0 ? 'text-danger' : 'text-dark' }}">
                        {{ number_format($stats['expiring_soon']) }}
                    </div>
                    <div class="small text-muted">Cần ưu tiên xử lý / tiêu hủy</div>
                </div>
                <div class="stat-icon bg-danger-subtle text-danger-emphasis">
                    <i class="bi bi-calendar-x-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
