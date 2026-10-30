@extends('layouts.app')

@section('title', 'Quản Lý Thuốc - Danh Sách Kho')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-prescription2 text-primary me-2"></i> Danh Sách Kho Thuốc</h4>
                <small class="text-muted">Quản lý nhập xuất, đơn giá, số lượng tồn kho và hạn sử dụng</small>
            </div>
            <div>
                <a href="{{ route('medicines.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Thêm Thuốc Mới</span>
                </a>
            </div>
        </div>

        <!-- Bộ lọc & Thanh tìm kiếm nâng cao -->
        <form action="{{ route('medicines.index') }}" method="GET" class="row g-2 mt-2 pt-3 border-top">
            <div class="col-md-4 col-lg-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Tìm tên hoặc mã thuốc..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3 col-lg-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 col-lg-2">
                <select name="stock_status" class="form-select form-select-sm">
                    <option value="">-- Tình trạng kho --</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Sắp hết hàng (≤10)</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Hết hàng (=0)</option>
                </select>
            </div>

            <div class="col-md-3 col-lg-2">
                <select name="expiry_status" class="form-select form-select-sm">
                    <option value="">-- Hạn sử dụng --</option>
                    <option value="expiring" {{ request('expiry_status') == 'expiring' ? 'selected' : '' }}>Sắp hết hạn (≤30 ngày)</option>
                    <option value="expired" {{ request('expiry_status') == 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                </select>
            </div>

            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i> Lọc
                </button>
                @if(request()->hasAny(['search', 'category_id', 'stock_status', 'expiry_status']))
                    <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-outline-secondary" title="Xóa bộ lọc">
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
                        <th class="ps-3" style="width: 110px;">Mã Thuốc</th>
                        <th>Tên Thuốc</th>
                        <th style="width: 160px;">Danh Mục</th>
                        <th class="text-center" style="width: 90px;">Đơn Vị</th>
                        <th class="text-end" style="width: 130px;">Đơn Giá</th>
                        <th class="text-center" style="width: 120px;">Tồn Kho</th>
                        <th class="text-center" style="width: 130px;">Hạn Dùng</th>
                        <th class="text-center pe-3" style="width: 150px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicines as $item)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace">{{ $item->code }}</span>
                            </td>
                            <td>
                                <a href="{{ route('medicines.show', $item) }}" class="fw-semibold text-decoration-none text-dark d-block">
                                    {{ $item->name }}
                                </a>
                                @if($item->supplier)
                                    <small class="text-muted"><i class="bi bi-truck me-1"></i>{{ $item->supplier->name }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis">
                                    {{ $item->category->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">{{ $item->unit }}</span>
                            </td>
                            <td class="text-end fw-bold text-success">
                                {{ $item->formatted_price }}
                            </td>
                            <td class="text-center">
                                @if($item->stock <= 0)
                                    <span class="badge bg-danger">Hết hàng</span>
                                @elseif($item->stock <= 10)
                                    <span class="badge bg-warning text-dark">{{ $item->stock }} (Ít)</span>
                                @else
                                    <span class="badge bg-success-subtle text-success-emphasis">{{ $item->stock }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div>{{ $item->expiry_date->format('d/m/Y') }}</div>
                                @if($item->isExpired())
                                    <span class="badge bg-danger" style="font-size: 0.65rem;">ĐÃ HẾT HẠN</span>
                                @elseif($item->isExpiringSoon())
                                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">SẮP HẾT HẠN</span>
                                @endif
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('medicines.show', $item) }}" class="btn btn-sm btn-outline-primary shadow-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('medicines.edit', $item) }}" class="btn btn-sm btn-outline-warning text-dark shadow-sm" title="Sửa thông tin">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('medicines.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thuốc {{ $item->name }} không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6>Không tìm thấy loại thuốc nào phù hợp</h6>
                                <p class="small mb-0">Thử thay đổi từ khóa tìm kiếm hoặc bấm <a href="{{ route('medicines.create') }}">Thêm thuốc mới</a>.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($medicines->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Hiển thị <strong>{{ $medicines->firstItem() }}</strong> - <strong>{{ $medicines->lastItem() }}</strong> trên tổng <strong>{{ $medicines->total() }}</strong> thuốc
            </div>
            <div>
                {{ $medicines->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
