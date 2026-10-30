@extends('layouts.app')

@section('title', 'Quản Lý Nhà Cung Cấp')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-truck text-primary me-2"></i> Quản Lý Nhà Cung Cấp Dược Phẩm</h4>
                <small class="text-muted">Quản lý thông tin các công ty, đối tác phân phối và cung ứng thuốc cho nhà thuốc</small>
            </div>
            <div>
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Thêm Nhà Cung Cấp</span>
                </a>
            </div>
        </div>

        <!-- Thanh tìm kiếm nhà cung cấp -->
        <form action="{{ route('suppliers.index') }}" method="GET" class="mt-3 pt-3 border-top">
            <div class="row g-2">
                <div class="col-md-5 col-lg-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo mã, tên, SĐT, địa chỉ..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-funnel me-1"></i> Tìm Kiếm
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 110px;">Mã NCC</th>
                        <th style="min-width: 220px;">Tên Nhà Cung Cấp</th>
                        <th style="width: 200px;">Liên Hệ</th>
                        <th>Địa Chỉ & Ghi Chú</th>
                        <th class="text-center" style="width: 130px;">Số Thuốc</th>
                        <th class="text-center pe-3" style="width: 150px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace">{{ $supplier->code }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold text-primary">{{ $supplier->name }}</div>
                            </td>
                            <td class="small">
                                @if($supplier->phone)
                                    <div><i class="bi bi-telephone text-muted me-1"></i> {{ $supplier->phone }}</div>
                                @endif
                                @if($supplier->email)
                                    <div class="text-muted"><i class="bi bi-envelope me-1"></i> {{ $supplier->email }}</div>
                                @endif
                                @if(!$supplier->phone && !$supplier->email)
                                    <span class="text-muted">Chưa cập nhật</span>
                                @endif
                            </td>
                            <td class="small text-secondary">
                                @if($supplier->address)
                                    <div><i class="bi bi-geo-alt text-muted me-1"></i> {{ $supplier->address }}</div>
                                @endif
                                @if($supplier->description)
                                    <div class="text-muted fst-italic mt-1">{{ $supplier->description }}</div>
                                @endif
                                @if(!$supplier->address && !$supplier->description)
                                    <span class="text-muted">Chưa có địa chỉ / ghi chú</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info-emphasis border">
                                    {{ $supplier->medicines_count ?? 0 }} loại thuốc
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning text-dark shadow-sm" title="Sửa nhà cung cấp">
                                        <i class="bi bi-pencil-square me-1"></i> Sửa
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp {{ $supplier->name }} không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Xóa">
                                            <i class="bi bi-trash me-1"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-truck fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6>Chưa có nhà cung cấp nào</h6>
                                <p class="small mb-0">Bấm <a href="{{ route('suppliers.create') }}">Thêm nhà cung cấp</a> để tạo đối tác đầu tiên.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($suppliers->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Hiển thị <strong>{{ $suppliers->firstItem() }}</strong> - <strong>{{ $suppliers->lastItem() }}</strong> trên tổng <strong>{{ $suppliers->total() }}</strong> nhà cung cấp
            </div>
            <div>
                {{ $suppliers->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
