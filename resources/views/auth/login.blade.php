@extends('layouts.app')

@section('title', 'Đăng Nhập Quản Trị')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 70vh; padding: 20px 0;">
    <div style="width: 100%; max-width: 420px;">
        <div class="ant-card" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);">
            <div class="ant-card-body" style="padding: 36px 32px;">
                <div style="text-align: center; margin-bottom: 28px;">
                    <div style="width: 52px; height: 52px; background: #e6f4ff; color: #1677ff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 12px;">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h2 style="margin: 0 0 6px 0; font-size: 22px; font-weight: 600; color: rgba(0, 0, 0, 0.88);">Đăng Nhập Quản Trị</h2>
                    <div style="color: rgba(0, 0, 0, 0.45); font-size: 13px;">Hệ thống quản lý kho thuốc PharmaCare</div>
                </div>

                <!-- Banner tài khoản demo -->
                <div style="background: #e6f4ff; border: 1px solid #91caff; border-radius: 6px; padding: 10px 14px; margin-bottom: 20px; font-size: 12px; color: #003eb3;">
                    <i class="fa-solid fa-circle-info" style="margin-right: 4px;"></i> <strong>Tài khoản có sẵn:</strong><br>
                    Email: <code>admin@gmail.com</code> | Mật khẩu: <code>12345678</code>
                </div>

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="ant-form-item">
                        <label class="ant-form-item-label required" for="email">Địa chỉ Email</label>
                        <div style="position: relative;">
                            <i class="fa-regular fa-envelope" style="position: absolute; left: 12px; top: 12px; color: rgba(0,0,0,0.35);"></i>
                            <input type="email" class="ant-input @error('email') ant-input-error @enderror" id="email" name="email" value="{{ old('email', 'admin@gmail.com') }}" placeholder="admin@gmail.com" style="padding-left: 36px;" required autofocus>
                        </div>
                        @error('email')
                            <span class="ant-input-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="ant-form-item">
                        <label class="ant-form-item-label required" for="password">Mật khẩu</label>
                        <div style="position: relative;">
                            <i class="fa-solid fa-key" style="position: absolute; left: 12px; top: 12px; color: rgba(0,0,0,0.35);"></i>
                            <input type="password" class="ant-input @error('password') ant-input-error @enderror" id="password" name="password" value="12345678" placeholder="Nhập mật khẩu" style="padding-left: 36px;" required>
                        </div>
                        @error('password')
                            <span class="ant-input-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <label style="font-size: 13px; color: rgba(0,0,0,0.65); display: flex; align-items: center; gap: 6px; cursor: pointer;">
                            <input type="checkbox" name="remember" checked> Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <button type="submit" class="ant-btn ant-btn-primary" style="width: 100%; height: 40px; font-size: 15px;">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Đăng Nhập
                    </button>
                </form>

                <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid #f0f0f0; font-size: 13px; color: rgba(0,0,0,0.45);">
                    Chưa có tài khoản quản trị?
                    <a href="{{ route('register') }}" style="color: #1677ff; text-decoration: none; font-weight: 500; margin-left: 4px;">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection