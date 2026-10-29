@extends('layouts.app')

@section('title', 'Tổng Quan Hoạt Động')

@section('content')
<!-- Header & Quick Actions -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-speedometer2 text-primary me-2"></i> Bảng Điều Khiển Tổng Quan</h3>
        <p class="text-muted small mb-0">Hệ thống tổng hợp số liệu doanh thu bán lẻ, tình hình kho vận và nhân sự</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('invoices.create') }}" class="btn btn-success d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-cart-plus"></i> Bán Thuốc Mới
        </a>
        <a href="{{ route('warehouse.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-boxes"></i> Lập Phiếu Kho
        </a>
        <a href="{{ route('medicines.create') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
            <i class="bi bi-capsule"></i> Thêm Thuốc
        </a>
    </div>
</div>

<!-- 4 Thẻ Tài Chính & Bán Hàng -->
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
                    <div class="text-uppercase small fw-bold opacity-75">Phiếu Nhập / Xuất Kho</div>
                    <div class="fs-4 fw-bold mt-1 text-info">{{ number_format($stats['total_warehouse_receipts']) }}</div>
                    <div class="small text-muted mt-1">Tổng cộng giao dịch kho</div>
                </div>
                <div class="stat-icon bg-info text-white shadow-sm">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
        </div>
    </div>

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

<!-- Row 1: Hóa đơn gần nhất & Phiếu kho gần nhất -->
<div class="row g-4 mb-4">
    <!-- Hóa Đơn Bán Hàng Gần Nhất -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-receipt text-success me-2"></i> Hóa Đơn Bán Gần Nhất
                </h5>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-success btn-sm">
                    Xem Tất Cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase text-secondary">
                            <tr>
                                <th class="ps-3">Mã HĐ</th>
                                <th>Khách Hàng</th>
                                <th class="text-end">Tổng Tiền</th>
                                <th class="text-center">Trạng Thái</th>
                                <th class="text-center pe-3">Xem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $inv)
                                <tr>
                                    <td class="ps-3">
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace">{{ $inv->code }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $inv->customer_name }}</div>
                                        <small class="text-muted">{{ $inv->created_at->format('d/m H:i') }}</small>
                                    </td>
                                    <td class="text-end fw-bold text-success">{{ $inv->formatted_total_amount }}</td>
                                    <td class="text-center">
                                        @if($inv->status === 'paid')
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Thành công</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-3">
                                        <a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có hóa đơn nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Phiếu Nhập / Xuất Kho Gần Nhất -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-boxes text-primary me-2"></i> Giao Dịch Kho Gần Nhất
                </h5>
                <a href="{{ route('warehouse.index') }}" class="btn btn-outline-primary btn-sm">
                    Xem Tất Cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase text-secondary">
                            <tr>
                                <th class="ps-3">Mã Phiếu</th>
                                <th class="text-center">Loại</th>
                                <th>Đối Tác</th>
                                <th class="text-end">Giá Trị</th>
                                <th class="text-center pe-3">Xem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReceipts as $rec)
                                <tr>
                                    <td class="ps-3">
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace">{{ $rec->code }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($rec->type === 'import')
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Nhập</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Xuất</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark text-truncate" style="max-width: 140px;">{{ $rec->supplier ?: 'Nội bộ' }}</div>
                                        <small class="text-muted">{{ $rec->created_at->format('d/m H:i') }}</small>
                                    </td>
                                    <td class="text-end fw-bold text-primary">{{ $rec->formatted_total_amount }}</td>
                                    <td class="text-center pe-3">
                                        <a href="{{ route('warehouse.show', $rec) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có giao dịch kho nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Thuốc mới nhập & Phân bổ danh mục -->
<div class="row g-4">
    <!-- Cột trái: Thuốc mới nhập gần nhất -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-capsule-pill text-info me-2"></i> Thuốc Cập Nhật Gần Nhất
                </h5>
                <a href="{{ route('medicines.index') }}" class="btn btn-outline-info btn-sm">
                    Xem Kho Thuốc <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase text-secondary">
                            <tr>
                                <th class="ps-3">Mã Thuốc</th>
                                <th>Tên Thuốc</th>
                                <th>Danh Mục</th>
                                <th class="text-end">Đơn Giá</th>
                                <th class="text-center">Tồn Kho</th>
                                <th class="text-center pe-3">Xem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMedicines as $med)
                                <tr>
                                    <td class="ps-3">
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace">{{ $med->code }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('medicines.show', $med) }}" class="fw-semibold text-decoration-none text-dark">
                                            {{ $med->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info-emphasis">{{ $med->category->name ?? 'Chưa phân loại' }}</span>
                                    </td>
                                    <td class="text-end fw-bold text-success">{{ $med->formatted_price }}</td>
                                    <td class="text-center">
                                        @if($med->stock <= 0)
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @elseif($med->stock <= 10)
                                            <span class="badge bg-warning text-dark">{{ $med->stock }} {{ $med->unit }}</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success-emphasis">{{ $med->stock }} {{ $med->unit }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-3">
                                        <a href="{{ route('medicines.show', $med) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Chưa có thuốc nào trong kho.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột phải: Phân bổ theo danh mục -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-pie-chart text-primary me-2"></i> Phân Bổ Theo Danh Mục
                </h5>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-primary btn-sm">
                    Quản Lý <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="card-body p-3">
                <ul class="list-group list-group-flush">
                    @forelse($categoryBreakdown as $cat)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-light">
                            <div class="d-flex align-items-center gap-2">
                                <span class="bg-primary-subtle text-primary p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                    <i class="bi bi-tag-fill" style="font-size: 0.75rem;"></i>
                                </span>
                                <span class="fw-medium text-dark">{{ $cat->name }}</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $cat->medicines_count }} loại</span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-3">Chưa có danh mục nào.</li>
                    @endforelse
                </ul>

                <div class="mt-3 pt-3 border-top d-grid gap-2">
                    <a href="{{ route('categories.create') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Danh Mục Mới
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
