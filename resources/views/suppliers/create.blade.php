@extends('layouts.app')

@section('title', 'Thêm Nhà Cung Cấp Mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm mb-1">
                    <i class="bi bi-arrow-left me-1"></i> Quay Lại Danh Sách
                </a>
                <h4 class="fw-bold mb-0">Thêm Nhà Cung Cấp Dược Phẩm</h4>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="code" class="form-label fw-semibold small">Mã Nhà Cung Cấp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="VD: NCC001, DHG..." required autofocus>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="name" class="form-label fw-semibold small">Tên Công Ty / Nhà Cung Cấp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Công ty Cổ phần Dược Hậu Giang..." required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold small">Số Điện Thoại Liên Hệ</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="VD: 0292 3891 433">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small">Địa Chỉ Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="VD: contact@dhgpharma.com.vn">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold small">Địa Chỉ Trụ Sở / Kho Phân Phối</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="VD: 288 Bis Nguyễn Văn Cừ, Q. Ninh Kiều, TP. Cần Thơ">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold small">Mô Tả / Ghi Chú Hợp Tác</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Các nhóm thuốc chủ력 cung cấp, chiết khấu hoặc điều khoản giao hàng...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary px-4">Hủy Bỏ</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Lưu Nhà Cung Cấp
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
