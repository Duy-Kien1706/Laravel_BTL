@extends('layouts.app')

@section('title', 'Chi Tiết Thuốc: ' . $medicine->name)

@section('content')
<div style="max-width: 860px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="{{ route('medicines.index') }}" class="ant-btn ant-btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Quay Lại Danh Sách
        </a>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('medicines.edit', $medicine) }}" class="ant-btn ant-btn-sm" style="color: #faad14; border-color: #faad14;">
                <i class="fa-regular fa-pen-to-square"></i> Chỉnh Sửa
            </a>
            <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thuốc {{ $medicine->name }} không?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="ant-btn ant-btn-sm ant-btn-danger">
                    <i class="fa-regular fa-trash-can"></i> Xóa
                </button>
            </form>
        </div>
    </div>

    <div class="ant-card">
        <div class="ant-card-body" style="padding: 32px;">
            <!-- Header section -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 24px; border-bottom: 1px solid #f0f0f0; margin-bottom: 24px;">
                <div>
                    <span class="ant-tag ant-tag-blue" style="font-size: 13px; margin-bottom: 8px;">{{ $medicine->category }}</span>
                    <h1 style="margin: 0 0 6px 0; font-size: 26px; font-weight: 600; color: rgba(0, 0, 0, 0.88);">{{ $medicine->name }}</h1>
                    <div style="font-size: 13px; color: rgba(0,0,0,0.45);">
                        Mã định danh: <span class="ant-tag" style="font-family: monospace;">{{ $medicine->code }}</span>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 12px; color: rgba(0,0,0,0.45); text-transform: uppercase;">Đơn giá niêm yết</div>
                    <div style="font-size: 26px; font-weight: 700; color: #52c41a; margin-top: 2px;">{{ $medicine->formatted_price }}</div>
                    <div style="font-size: 12px; color: rgba(0,0,0,0.45);">/ {{ $medicine->unit }}</div>
                </div>
            </div>

            <!-- Indicators Grid -->
            <div class="ant-grid-row" style="margin-bottom: 24px;">
                <div class="ant-col-12">
                    <div style="background: #fafafa; border: 1px solid #f0f0f0; border-radius: 8px; padding: 16px;">
                        <div style="font-size: 13px; color: rgba(0,0,0,0.45); margin-bottom: 6px;">
                            <i class="fa-solid fa-boxes-packing" style="margin-right: 4px;"></i> Tình Trạng Tồn Kho
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <span style="font-size: 24px; font-weight: 600; color: rgba(0,0,0,0.85);">{{ $medicine->stock }}</span>
                                <span style="color: rgba(0,0,0,0.45); font-size: 14px; margin-left: 4px;">({{ $medicine->unit }})</span>
                            </div>
                            <div>
                                @if($medicine->stock <= 0)
                                    <span class="ant-tag ant-tag-red">Hết hàng</span>
                                @elseif($medicine->stock <= 10)
                                    <span class="ant-tag ant-tag-orange">Cảnh báo: Sắp hết kho!</span>
                                @else
                                    <span class="ant-tag ant-tag-green">Kho ổn định</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ant-col-12">
                    <div style="background: #fafafa; border: 1px solid #f0f0f0; border-radius: 8px; padding: 16px;">
                        <div style="font-size: 13px; color: rgba(0,0,0,0.45); margin-bottom: 6px;">
                            <i class="fa-regular fa-calendar-check" style="margin-right: 4px;"></i> Hạn Sử Dụng
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 24px; font-weight: 600; color: rgba(0,0,0,0.85);">{{ $medicine->expiry_date->format('d/m/Y') }}</span>
                            <div>
                                @if($medicine->isExpired())
                                    <span class="ant-tag ant-tag-red">ĐÃ HẾT HẠN</span>
                                @elseif($medicine->isExpiringSoon())
                                    <span class="ant-tag ant-tag-orange">SẮP HẾT HẠN</span>
                                @else
                                    <span class="ant-tag ant-tag-green">Còn hạn tốt</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 10px 0; font-size: 15px; font-weight: 600; color: rgba(0,0,0,0.88);">
                    <i class="fa-regular fa-file-lines" style="color: #1677ff; margin-right: 6px;"></i> Mô Tả & Hướng Dẫn Sử Dụng
                </h4>
                <div style="background: #fafafa; border: 1px solid #f0f0f0; border-radius: 8px; padding: 16px; font-size: 14px; line-height: 1.6; color: rgba(0,0,0,0.75); white-space: pre-line;">
                    @if($medicine->description)
                        {{ $medicine->description }}
                    @else
                        <span style="color: rgba(0,0,0,0.35); font-style: italic;">Chưa có mô tả chi tiết cho loại thuốc này.</span>
                    @endif
                </div>
            </div>

            <!-- Audit info -->
            <div style="display: flex; justify-content: space-between; font-size: 12px; color: rgba(0,0,0,0.45); padding-top: 16px; border-top: 1px solid #f0f0f0;">
                <div>Ngày nhập kho: <strong>{{ $medicine->created_at ? $medicine->created_at->format('d/m/Y H:i') : 'N/A' }}</strong></div>
                <div>Cập nhật lần cuối: <strong>{{ $medicine->updated_at ? $medicine->updated_at->format('d/m/Y H:i') : 'N/A' }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection