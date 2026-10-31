@extends('layouts.app')

@section('title', 'Đăng Nhập Quản Trị')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 54px; height: 54px;">
                        <i class="bi bi-shield-lock-fill fs-3"></i>
                    </div>
                    <h4 class="fw-bold mb-1">Đăng Nhập Quản Trị</h4>
                    <p class="text-muted small">Nhập thông tin tài khoản để truy cập hệ thống thuốc</p>
                </div>
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold">Địa chỉ Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', 'admin@gmail.com') }}" placeholder="admin@gmail.com" required autofocus>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-key"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="12345678" placeholder="Nhập mật khẩu" required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" checked>
                        <label class="form-check-label small" for="remember">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng Nhập
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top text-muted small">
                    <i class="bi bi-shield-lock me-1"></i> Hệ thống quản lý nội bộ PharmaCare.
                    <div class="mt-1">Tài khoản do Quản trị viên cấp. Vui lòng liên hệ Admin nếu cần hỗ trợ.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
