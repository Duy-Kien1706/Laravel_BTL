@extends('layouts.app')

@section('title', 'Quản Lý Danh Mục Thuốc')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-tags text-primary me-2"></i> Danh Mục Thuốc (Categories)</h4>
                <small class="text-muted">Quản lý các nhóm thuốc điều trị và số lượng sản phẩm liên kết</small>
            </div>
            <div>
                <a href="{{ route('categories.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Thêm Danh Mục Mới</span>
                </a>
            </div>
        </div>

        <!-- Thanh tìm kiếm danh mục -->
        <form action="{{ route('categories.index') }}" method="GET" class="mt-3 pt-3 border-top">
            <div class="row g-2">
                <div class="col-md-5 col-lg-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Tìm tên hoặc mô tả danh mục..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-funnel me-1"></i> Tìm Kiếm
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">
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
                        <th class="ps-3" style="width: 80px;">ID</th>
                        <th style="width: 250px;">Tên Danh Mục</th>
                        <th>Mô Tả Chi Tiết</th>
                        <th class="text-center" style="width: 140px;">Số Lượng Thuốc</th>
                        <th class="text-center" style="width: 150px;">Ngày Tạo</th>
                        <th class="text-center pe-3" style="width: 150px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td class="ps-3 fw-bold text-muted">#{{ $cat->id }}</td>
                            <td>
                                <span class="fw-semibold text-primary fs-6">{{ $cat->name }}</span>
                            </td>
                            <td class="text-secondary small">
                                {{ $cat->description ?: 'Chưa có mô tả chi tiết.' }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('medicines.index', ['category_id' => $cat->id]) }}" class="badge bg-primary text-decoration-none" title="Xem danh sách thuốc thuộc nhóm này">
                                    {{ $cat->medicines_count }} loại thuốc
                                </a>
                            </td>
                            <td class="text-center text-muted small">
                                {{ $cat->created_at ? $cat->created_at->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-warning text-dark shadow-sm" title="Sửa danh mục">
                                        <i class="bi bi-pencil-square me-1"></i> Sửa
                                    </a>
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục {{ $cat->name }} không?')">
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
                                <i class="bi bi-tags fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6>Chưa có danh mục thuốc nào</h6>
                                <p class="small mb-0">Bấm <a href="{{ route('categories.create') }}">Thêm danh mục mới</a> để tạo nhóm đầu tiên.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($categories->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Hiển thị <strong>{{ $categories->firstItem() }}</strong> - <strong>{{ $categories->lastItem() }}</strong> trên tổng <strong>{{ $categories->total() }}</strong> danh mục
            </div>
            <div>
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
