@extends('layouts.app')

@section('title', 'Chỉnh Sửa Thuốc: ' . $medicine->name)

@section('content')
<div style="max-width: 860px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <a href="{{ route('medicines.index') }}" class="ant-btn ant-btn-sm" style="margin-bottom: 8px;">
                <i class="fa-solid fa-arrow-left"></i> Quay Lại Danh Sách
            </a>
            <h2 style="margin: 0; font-size: 22px; font-weight: 600; color: rgba(0, 0, 0, 0.88);">Chỉnh Sửa Thông Tin Thuốc</h2>
        </div>
        <div>
            <span class="ant-tag" style="font-family: monospace; font-size: 13px;">{{ $medicine->code }}</span>
        </div>
    </div>

    <div class="ant-card">
        <div class="ant-card-body" style="padding: 32px;">
            <form action="{{ route('medicines.update', $medicine) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="ant-grid-row">
                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="code">Mã Thuốc</label>
                            <input type="text" class="ant-input @error('code') ant-input-error @enderror" id="code" name="code" value="{{ old('code', $medicine->code) }}" required>
                            @error('code')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="name">Tên Thuốc</label>
                            <input type="text" class="ant-input @error('name') ant-input-error @enderror" id="name" name="name" value="{{ old('name', $medicine->name) }}" required>
                            @error('name')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-12">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="category">Nhóm / Danh Mục Thuốc</label>
                            <input list="category-options" class="ant-input @error('category') ant-input-error @enderror" id="category" name="category" value="{{ old('category', $medicine->category) }}" required>
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
                            <input list="unit-options" class="ant-input @error('unit') ant-input-error @enderror" id="unit" name="unit" value="{{ old('unit', $medicine->unit) }}" required>
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
                                <input type="number" step="500" min="0" class="ant-input @error('price') ant-input-error @enderror" id="price" name="price" value="{{ old('price', (int)$medicine->price) }}" style="padding-right: 32px;" required>
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
                            <input type="number" min="0" class="ant-input @error('stock') ant-input-error @enderror" id="stock" name="stock" value="{{ old('stock', $medicine->stock) }}" required>
                            @error('stock')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-24">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label required" for="expiry_date">Hạn Sử Dụng</label>
                            <input type="date" class="ant-input @error('expiry_date') ant-input-error @enderror" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $medicine->expiry_date->format('Y-m-d')) }}" required>
                            @error('expiry_date')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="ant-col-24">
                        <div class="ant-form-item">
                            <label class="ant-form-item-label" for="description">Mô Tả & Hướng Dẫn Sử Dụng</label>
                            <textarea class="ant-input @error('description') ant-input-error @enderror" id="description" name="description" rows="4" style="height: auto;">{{ old('description', $medicine->description) }}</textarea>
                            @error('description')
                                <span class="ant-input-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                    <a href="{{ route('medicines.index') }}" class="ant-btn">Hủy Bỏ</a>
                    <button type="submit" class="ant-btn ant-btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection