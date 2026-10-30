@extends('layouts.app')

@section('title', 'Quản Lý Nhân Viên')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-people-fill text-primary me-2"></i> Quản Lý Nhân Viên</h4>
                <small class="text-muted">Danh sách tài khoản nhân sự, phân quyền vai trò và trạng thái công tác</small>
            </div>
            <div>
                <a href="{{ route('staff.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Thêm Nhân Viên Mới</span>
                </a>
            </div>
        </div>

        <!-- Bộ lọc & Tìm kiếm nhân viên -->
        <form action="{{ route('staff.index') }}" method="GET" class="row g-2 mt-2 pt-3 border-top">
            <div class="col-md-5 col-lg-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tên, email, số điện thoại..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3 col-lg-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">-- Tất cả chức vụ --</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                    <option value="pharmacist" {{ request('role') == 'pharmacist' ? 'selected' : '' }}>Dược sĩ bán hàng</option>
                    <option value="warehouse" {{ request('role') == 'warehouse' ? 'selected' : '' }}>Thủ kho dược phẩm</option>
                </select>
            </div>

            <div class="col-md-2 col-lg-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang làm việc</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Đã nghỉ việc</option>
                </select>
            </div>

            <div class="col-md-2 col-lg-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i> Lọc
                </button>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('staff.index') }}" class="btn btn-sm btn-outline-secondary" title="Xóa bộ lọc">
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
                        <th class="ps-3" style="width: 70px;">ID</th>
                        <th>Họ Và Tên</th>
                        <th>Email & Điện Thoại</th>
                        <th class="text-center" style="width: 170px;">Chức Vụ</th>
                        <th class="text-end" style="width: 140px;">Lương Cơ Bản</th>
                        <th class="text-center" style="width: 130px;">Trạng Thái</th>
                        <th class="text-center" style="width: 120px;">Ngày Tạo</th>
                        <th class="text-center pe-3" style="width: 130px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffs as $staff)
                        <tr>
                            <td class="ps-3 fw-bold text-muted">#{{ $staff->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $staff->name }}</div>
                                        @if($staff->id === Auth::id())
                                            <span class="badge bg-secondary-subtle text-secondary-emphasis" style="font-size: 0.65rem;">(Tài khoản của bạn)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i class="bi bi-envelope text-muted me-1"></i> {{ $staff->email }}</div>
                                @if($staff->phone)
                                    <div class="small text-muted"><i class="bi bi-telephone text-muted me-1"></i> {{ $staff->phone }}</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($staff->role === 'admin')
                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">
                                        <i class="bi bi-shield-lock-fill me-1"></i> Quản trị viên
                                    </span>
                                @elseif($staff->role === 'pharmacist')
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                        <i class="bi bi-capsule me-1"></i> Dược sĩ
                                    </span>
                                @else
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                        <i class="bi bi-boxes me-1"></i> Thủ kho
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold text-dark">
                                {{ $staff->formatted_salary }}
                            </td>
                            <td class="text-center">
                                @if($staff->status === 'active')
                                    <span class="badge bg-success">Đang làm việc</span>
                                @else
                                    <span class="badge bg-secondary">Đã nghỉ</span>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                {{ $staff->created_at ? $staff->created_at->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('staff.edit', $staff) }}" class="btn btn-sm btn-outline-warning text-dark shadow-sm" title="Sửa thông tin">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @if($staff->id !== Auth::id())
                                        <form action="{{ route('staff.destroy', $staff) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên {{ $staff->name }} không?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Xóa nhân viên">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                <h6>Không tìm thấy nhân viên nào phù hợp</h6>
                                <p class="small mb-0">Thử thay đổi từ khóa tìm kiếm hoặc bấm <a href="{{ route('staff.create') }}">Thêm nhân viên mới</a>.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($staffs->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Hiển thị <strong>{{ $staffs->firstItem() }}</strong> - <strong>{{ $staffs->lastItem() }}</strong> trên tổng <strong>{{ $staffs->total() }}</strong> nhân viên
            </div>
            <div>
                {{ $staffs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
