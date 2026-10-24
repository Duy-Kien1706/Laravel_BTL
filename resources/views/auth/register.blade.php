@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản Quản Trị')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 70vh; padding: 20px 0;">
    <div style="width: 100%; max-width: 460px;">
        <div class="ant-card" style="box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);">
            <div class="ant-card-body" style="padding: 36px 32px;">
                <div style="text-align: center; margin-bottom: 28px;">
                    <div style="width: 52px; height: 52px; background: #f6ffed; color: #52c41a; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 12px;">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <h2 style="margin: 0 0 6px 0; font-size: 22px; font-weight: 600; color: rgba(0, 0, 0, 0.88);">Đăng Ký Tài Khoản</h2>
                    <div style="color: rgba(0, 0, 0, 0.45); font-size: 13px;">Tạo tài khoản quản trị viên mới cho PharmaCare</div>
                </div>

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="ant-form-item">
                        <label class="ant-form-item-label required" for="name">Họ và Tên</label>
                        <div style="position: relative;">
                            <i class="fa-regular fa-user" style="position: absolute; left: 12px; top: 12px; color: rgba(0,0,0,0.35);"></i>
                            <input type="text" class="ant-input @error('name') ant-input-error @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Nguyễn Văn A" style="padding-left: 36px;" required autofocus>
                        </div>
                        @error('name')
                            <span class="ant-input-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="ant-form-item">
                        <label class="ant-form-item-label required" for="email">Địa chỉ Email</label>
                        <div style="position: relative;">
                            <i class="fa-regular fa-envelope" style="position: absolute; left: 12px; top: 12px; color: rgba(0,0,0,0.35);"></i>
                            <input type="email" class="ant-input @error('email') ant-input-error @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="admin@domain.com" style="padding-left: 36px;" required>
                        </div>
                        @error('email')
                            <span class="ant-input-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="ant-form-item">
                        <label class="ant-form-item-label required" for="password">Mật khẩu (Tối thiểu 6 ký tự)</label>
                        <div style="position: relative;">
                            <i class="fa-solid fa-key" style="position: absolute; left: 12px; top: 12px; color: rgba(0,0,0,0.35);"></i>
                            <input type="password" class="ant-input @error('password') ant-input-error @enderror" id="password" name="password" placeholder="Nhập mật khẩu mới" style="padding-left: 36px;" required>
                        </div>
                        @error('password')
                            <span class="ant-input-error-msg">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="ant-form-item">
                        <label class="ant-form-item-label required" for="password_confirmation">Xác nhận mật khẩu</label>
                        <div style="position: relative;">
                            <i class="fa-solid fa-shield-check" style="position: absolute; left: 12px; top: 12px; color: rgba(0,0,0,0.35);"></i>
                            <input type="password" class="ant-input" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu" style="padding-left: 36px;" required>
                        </div>
                    </div>

                    <button type="submit" class="ant-btn ant-btn-primary" style="width: 100%; height: 40px; font-size: 15px; background: #52c41a; border-color: #52c41a;">
                        <i class="fa-solid fa-check"></i> Hoàn Tất Đăng Ký
                    </button>
                </form>

                <div style="text-align: center; margin-top: 24px; padding-top: 16px; border-top: 1px solid #f0f0f0; font-size: 13px; color: rgba(0,0,0,0.45);">
                    Đã có tài khoản quản trị?
                    <a href="{{ route('login') }}" style="color: #1677ff; text-decoration: none; font-weight: 500; margin-left: 4px;">Đăng nhập tại đây</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection