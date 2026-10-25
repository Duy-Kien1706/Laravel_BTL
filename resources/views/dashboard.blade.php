@extends('layouts.app')

@section('title', 'Tổng Quan Kho Thuốc')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-speedometer2 text-primary me-2"></i> Tổng Quan Kho Thuốc</h3>
        <p class="text-muted small mb-0">Báo cáo tổng hợp tình hình tồn kho, hạn dùng và phân bổ danh mục thuốc</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('medicines.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
            <i class="bi bi-plus-circle"></i> Thêm Thuốc Mới
        </a>
        <a href="{{ route('categories.create') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1">
            <i class="bi bi-tag"></i> Thêm Danh Mục
        </a>
    </div>
</div>

<!-- 5 Thẻ Thống Kê Nhanh -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4 col-xl-2 flex-fill">
        <div class="card h-100 border-0 bg-primary-subtle text-primary-emphasis shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold opacity-75">Tổng Loại Thuốc</div>
                    <div class="fs-3 fw-bold mt-1">{{ number_format($stats['total_medicines']) }}</div>
                </div>
                <div class="stat-icon bg-primary text-white">
                    <i class="bi bi-capsule"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-4 col-xl-2 flex-fill">
        <div class="card h-100 border-0 bg-info-subtle text-info-emphasis shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold opacity-75">Tổng Danh Mục</div>
                    <div class="fs-3 fw-bold mt-1">{{ number_format($stats['total_categories']) }}</div>
                </div>
                <div class="stat-icon bg-info text-white">
                    <i class="bi bi-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-4 col-xl-2 flex-fill">
        <div class="card h-100 border-0 bg-success-subtle text-success-emphasis shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold opacity-75">Tổng Số Tồn Kho</div>
                    <div class="fs-3 fw-bold mt-1">{{ number_format($stats['total_stock']) }}</div>
                </div>
                <div class="stat-icon bg-success text-white">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-4 col-xl-2 flex-fill">
        <div class="card h-100 border-0 bg-warning-subtle text-warning-emphasis shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold opacity-75">Sắp Hết Hàng (≤10)</div>
                    <div class="fs-3 fw-bold mt-1">{{ number_format($stats['low_stock']) }}</div>
                </div>
                <div class="stat-icon bg-warning text-dark">
                    <i class="bi bi-exclamation-diamond-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-4 col-xl-2 flex-fill">
        <div class="card h-100 border-0 bg-danger-subtle text-danger-emphasis shadow-sm">
            <div class="card-body d-flex align-items-center justify-content-between p-3">
                <div>
                    <div class="text-uppercase small fw-bold opacity-75">Sắp Hết Hạn (≤30 ngày)</div>
                    <div class="fs-3 fw-bold mt-1">{{ number_format($stats['expiring_soon']) }}</div>
                </div>
                <div class="stat-icon bg-danger text-white">
                    <i class="bi bi-calendar-x-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Cột trái: Thuốc mới nhập gần nhất -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-clock-history text-primary me-2"></i> Thuốc Mới Nhập Gần Nhất
                </h5>
                <a href="{{ route('medicines.index') }}" class="btn btn-outline-primary btn-sm">
                    Xem Tất Cả Thuốc <i class="bi bi-arrow-right"></i>
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
                                            <span class="badge bg-warning text-dark">{{ $med->stock }}</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success-emphasis">{{ $med->stock }}</span>
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
                    <i class="bi bi-pie-chart text-info me-2"></i> Danh Mục Thuốc
                </h5>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-info btn-sm">
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
