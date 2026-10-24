@extends('layouts.app')

@section('title', 'Thêm Thuốc Mới')

@section('content')
<div style="max-width: 860px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <a href="{{ route('medicines.index') }}" class="ant-btn ant-btn-sm" style="margin-bottom: 8px;">
                <i class="fa-solid fa-arrow-left"></i> Quay Lại Danh Sách
            </a>
            <h2 style="margin: 0; font-size: 22px; font-weight: 600; color: rgba(0, 0, 0, 0.88);">Thêm Thuốc Mới Vào Kho</h2>
        </div>
    </div>

    <div class="ant-card">
        <div class="ant-card-body" style="padding: 32px;">
            <form action="{{ route('medicines.store') }}" method="POST">
                @csrf

                <div class="ant-grid-row">
                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="code">Mã Thuốc (Duy nhất)</label>
                            <input type="text" class="ant-input @error('code') ant-input-error @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="VD: TH001, PARA500..." required>
                            @error('code')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="name">Tên Thuốc</label>
                            <input type="text" class="ant-input @error('name') ant-input-error @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="VD: Paracetamol 500mg, Panadol..." required>
                            @error('name')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="category">Nhóm / Danh Mục Thuốc</label>
                            <input list="category-options" class="ant-input @error('category') ant-input-error @enderror" id="category" name="category" value="{{ old('category') }}" placeholder="Chọn hoặc nhập nhóm thuốc..." required>
                            <datalist id="category-options">
                                @foreach($defaultCategories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            @error('category')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="unit">Đơn Vị Tính</label>
                            <input list="unit-options" class="ant-input @error('unit') ant-input-error @enderror" id="unit" name="unit" value="{{ old('unit') }}" placeholder="Chọn hoặc nhập đơn vị tính..." required>
                            <datalist id="unit-options">
                                @foreach($defaultUnits as $u)
                                    <option value="{{ $u }}">
                                @endforeach
                            </datalist>
                            @error('unit')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="price">Đơn Giá Bán (VNĐ)</label>
                            <div style="position: relative;">
                                <input type="number" step="500" min="0" class="ant-input @error('price') ant-input-error @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="VD: 35000" style="padding-right: 32px;" required>
                                <span style="position: absolute; right: 12px; top: 9px; color: rgba(0,0,0,0.45); font-size: 13px;">đ</span>
                            </div>
                            @error('price')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="stock">Số Lượng Tồn Kho</label>
                            <input type="number" min="0" class="ant-input @error('stock') ant-input-error @enderror" id="stock" name="stock" value="{{ old('stock', 100) }}" placeholder="VD: 50" required>
                            @error('stock')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-24">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="expiry_date">Hạn Sử Dụng</label>
                            <input type="date" class="ant-input @error('expiry_date') ant-input-error @enderror" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', now()->addYears(2)->format('Y-m-d')) }}" required>
                            @error('expiry_date')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-24">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label" for="description">Mô Tả & Hướng Dẫn Sử Dụng</label>
                            <textarea class="ant-input @error('description') ant-input-error @enderror" id="description" name="description" rows="4" style="height: auto;" placeholder="Công dụng, liều dùng, chống chỉ định, điều kiện bảo quản...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                    <a href="{{ route('medicines.index') }}" class="ant-btn">Hủy Bỏ</a>
                    <button type="submit" class="ant-btn ant-btn-primary">
                        <i class="fa-solid fa-check"></i> Lưu Thuốc Vào Kho
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection