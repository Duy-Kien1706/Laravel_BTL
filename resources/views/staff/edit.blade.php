@extends('layouts.app')

@section('title', 'Chỉnh Sửa Nhân Viên: ' . $staff->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary btn-sm mb-1">
                    <i class="bi bi-arrow-left me-1"></i> Quay Lại Danh Sách
                </a>
                <h4 class="fw-bold mb-0">Chỉnh Sửa Thông Tin Nhân Viên</h4>
            </div>
            <div>
                <span class="badge bg-secondary">ID #{{ $staff->id }}</span>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('staff.update', $staff) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold small">Họ và Tên Nhân Viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $staff->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small">Địa chỉ Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $staff->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold small">Số Điện Thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $staff->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label fw-semibold small">Chức Vụ / Vai Trò <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="pharmacist" {{ old('role', $staff->role) == 'pharmacist' ? 'selected' : '' }}>Dược sĩ bán hàng</option>
                                <option value="admin" {{ old('role', $staff->role) == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="salary" class="form-label fw-semibold small">Lương Cơ Bản (VNĐ)</label>
                            <div class="input-group">
                                <input type="number" step="500000" min="0" class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary', (int)$staff->salary) }}">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('salary')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold small">Trạng Thái Làm Việc <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $staff->status) == 'active' ? 'selected' : '' }}>Đang làm việc</option>
                                <option value="inactive" {{ old('status', $staff->status) == 'inactive' ? 'selected' : '' }}>Đã nghỉ việc</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label fw-semibold small">Đổi Mật Khẩu Mới (Để trống nếu giữ nguyên)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Nhập mật khẩu mới nếu muốn đổi">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('staff.index') }}" class="btn btn-outline-secondary px-4">Hủy Bỏ</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-save me-1"></i> Cập Nhật Nhân Viên
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
