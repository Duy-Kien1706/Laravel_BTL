@extends('layouts.app')

@section('title', 'Quản Lý Kho Dược Phẩm')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-primary me-2"></i> Quản Lý Kho (Nhập / Xuất Kho)</h4>
                <small class="text-muted">Theo dõi lịch sử nhập thuốc từ nhà cung cấp và xuất kho điều chuyển</small>
            </div>
            <div>
                <a href="{{ route('warehouse.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Lập Phiếu Kho Mới</span>
                </a>
            </div>
        </div>

        <!-- Bộ lọc & Tìm kiếm phiếu kho -->
        <form action="{{ route('warehouse.index') }}" method="GET" class="row g-2 mt-2 pt-3 border-top">
            <div class="col-md-5 col-lg-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo mã phiếu, nhà cung cấp..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3 col-lg-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">-- Tất cả loại phiếu --</option>
                    <option value="import" {{ request('type') == 'import' ? 'selected' : '' }}>Phiếu Nhập Kho</option>
                    <option value="export" {{ request('type') == 'export' ? 'selected' : '' }}>Phiếu Xuất Kho</option>
                </select>
            </div>

            <div class="col-md-4 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i> Lọc
                </button>
                @if(request()->hasAny(['search', 'type']))
                    <a href="{{ route('warehouse.index') }}" class="btn btn-sm btn-outline-secondary" title="Xóa bộ lọc">
                        <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 140px;">Mã Phiếu</th>
                        <th class="text-center" style="width: 130px;">Loại Phiếu</th>
                        <th>Nhà Cung Cấp / Đối Tác</th>
                        <th>Người Lập Phiếu</th>
                        <th class="text-end" style="width: 150px;">Tổng Giá Trị</th>
                        <th class="text-center" style="width: 140px;">Ngày Tạo</th>
                        <th class="text-center pe-3" style="width: 110px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $item)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace fs-6">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($item->type === 'import')
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                        <i class="bi bi-arrow-down-left-square me-1"></i> Nhập kho
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="bi bi-arrow-up-right-square me-1"></i> Xuất kho
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $item->supplier ?: 'Không ghi nhận' }}</div>
                                @if($item->note)
                                    <div class="small text-muted text-truncate" style="max-width: 280px;">{{ $item->note }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark"><i class="bi bi-person me-1"></i> {{ $item->user->name ?? 'N/A' }}</div>
                            </td>
                            <td class="text-end fw-bold text-primary">
                                {{ $item->formatted_total_amount }}
                            </td>
                            <td class="text-center text-muted small">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="text-center pe-3">
                                <a href="{{ route('warehouse.show', $item) }}" class="btn btn-sm btn-outline-info shadow-sm" title="Xem chi tiết phiếu kho">
                                    <i class="bi bi-eye me-1"></i> Xem
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-boxes fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6>Chưa có phiếu nhập / xuất kho nào</h6>
                                <p class="small mb-0">Bấm nút <a href="{{ route('warehouse.create') }}">Lập phiếu kho mới</a> để nhập hoặc xuất thuốc.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($receipts->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Hiển thị <strong>{{ $receipts->firstItem() }}</strong> - <strong>{{ $receipts->lastItem() }}</strong> trên tổng <strong>{{ $receipts->total() }}</strong> phiếu kho
            </div>
            <div>
                {{ $receipts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
