@extends('layouts.app')

@section('title', 'Chi Tiết Thuốc: ' . $medicine->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay Lại Danh Sách
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-warning btn-sm text-dark fw-semibold">
                    <i class="bi bi-pencil-square me-1"></i> Chỉnh Sửa
                </a>
                <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thuốc {{ $medicine->name }} không?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm fw-semibold">
                        <i class="bi bi-trash me-1"></i> Xóa
                    </button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 pb-3 border-bottom mb-4">
                    <div>
                        <span class="badge bg-info-subtle text-info-emphasis fs-6 mb-2">
                            <i class="bi bi-tag-fill me-1"></i> {{ $medicine->category->name ?? 'Chưa phân loại' }}
                        </span>
                        <h3 class="fw-bold mb-1 text-dark">{{ $medicine->name }}</h3>
                        <div class="text-muted small">
                            Mã thuốc hệ thống: <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace">{{ $medicine->code }}</span>
                        </div>
                    </div>
                    <div class="text-md-end">
                        <div class="text-muted small">Đơn giá bán</div>
                        <h3 class="fw-bold text-success mb-0">{{ $medicine->formatted_price }}</h3>
                        <small class="text-muted">/ {{ $medicine->unit }}</small>
                    </div>
                </div>

                <!-- Status indicators -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light border">
                            <div class="text-muted small mb-1"><i class="bi bi-boxes me-1"></i> Tình Trạng Tồn Kho</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4 fw-bold">{{ $medicine->stock }}</span>
                                <span class="text-muted">({{ $medicine->unit }})</span>
                                @if($medicine->stock <= 0)
                                    <span class="badge bg-danger ms-auto">Hết hàng</span>
                                @elseif($medicine->stock <= 10)
                                    <span class="badge bg-warning text-dark ms-auto">Cảnh báo: Sắp hết kho!</span>
                                @else
                                    <span class="badge bg-success ms-auto">Kho ổn định</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light border">
                            <div class="text-muted small mb-1"><i class="bi bi-calendar-check me-1"></i> Hạn Sử Dụng</div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-4 fw-bold">{{ $medicine->expiry_date->format('d/m/Y') }}</span>
                                @if($medicine->isExpired())
                                    <span class="badge bg-danger ms-auto">ĐÃ HẾT HẠN</span>
                                @elseif($medicine->isExpiringSoon())
                                    <span class="badge bg-warning text-dark ms-auto">Cảnh báo: Sắp hết hạn!</span>
                                @else
                                    <span class="badge bg-success ms-auto">Còn hạn tốt</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description & Guide -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark"><i class="bi bi-card-text me-1 text-primary"></i> Mô Tả & Hướng Dẫn Sử Dụng</h6>
                    <div class="p-3 rounded bg-light border">
                        @if($medicine->description)
                            <p class="mb-0 text-secondary" style="white-space: pre-line;">{{ $medicine->description }}</p>
                        @else
                            <span class="text-muted fst-italic">Chưa có thông tin mô tả chi tiết cho loại thuốc này.</span>
                        @endif
                    </div>
                </div>

                <!-- Audit info -->
                <div class="d-flex justify-content-between text-muted small pt-3 border-top">
                    <div>Ngày nhập kho: <strong>{{ $medicine->created_at ? $medicine->created_at->format('d/m/Y H:i') : 'N/A' }}</strong></div>
                    <div>Cập nhật lần cuối: <strong>{{ $medicine->updated_at ? $medicine->updated_at->format('d/m/Y H:i') : 'N/A' }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
