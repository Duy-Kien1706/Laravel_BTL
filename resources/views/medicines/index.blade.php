@extends('layouts.app')

@section('title', 'Quản Lý Thuốc - Danh Sách Thuốc')

@section('content')
<!-- Main CRUD Table Card -->
<div class="ant-card">
    <!-- Header: Tiêu đề và nút Thêm mới -->
    <div style="padding: 20px 24px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: rgba(0, 0, 0, 0.88); display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-pills" style="color: #1677ff;"></i> Danh Sách Thuốc
            </h3>
            <div style="font-size: 13px; color: rgba(0,0,0,0.45); margin-top: 2px;">
                Quản lý thông tin thuốc: Thêm, Xem chi tiết, Chỉnh sửa và Xóa
            </div>
        </div>
        <div>
            <a href="{{ route('medicines.create') }}" class="ant-btn ant-btn-primary">
                <i class="fa-solid fa-plus"></i> Thêm Thuốc Mới
            </a>
        </div>
    </div>

    <!-- Ant Design Table -->
    <div class="ant-table" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th style="width: 110px;">Mã Thuốc</th>
                    <th>Tên Thuốc</th>
                    <th style="width: 140px;">Nhóm Thuốc</th>
                    <th style="width: 90px; text-align: center;">Đơn Vị</th>
                    <th style="width: 130px; text-align: right;">Đơn Giá</th>
                    <th style="width: 110px; text-align: center;">Tồn Kho</th>
                    <th style="width: 130px; text-align: center;">Hạn Dùng</th>
                    <th style="width: 160px; text-align: center;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $item)
                    <tr>
                        <td>
                            <span class="ant-tag" style="font-family: monospace; font-weight: 600;">{{ $item->code }}</span>
                        </td>
                        <td>
                            <a href="{{ route('medicines.show', $item) }}" style="color: rgba(0,0,0,0.85); font-weight: 600; text-decoration: none; font-size: 14px;">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td>
                            <span class="ant-tag ant-tag-blue">{{ $item->category }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="ant-tag">{{ $item->unit }}</span>
                        </td>
                        <td style="text-align: right; font-weight: 600; color: #52c41a; font-size: 14px;">
                            {{ $item->formatted_price }}
                        </td>
                        <td style="text-align: center;">
                            @if($item->stock <= 0)
                                <span class="ant-tag ant-tag-red">Hết hàng</span>
                            @elseif($item->stock <= 10)
                                <span class="ant-tag ant-tag-orange">{{ $item->stock }}</span>
                            @else
                                <span class="ant-tag ant-tag-green">{{ $item->stock }}</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div>{{ $item->expiry_date->format('d/m/Y') }}</div>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: inline-flex; gap: 6px;">
                                <a href="{{ route('medicines.show', $item) }}" class="ant-btn ant-btn-sm" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i> Xem
                                </a>
                                <a href="{{ route('medicines.edit', $item) }}" class="ant-btn ant-btn-sm" style="color: #faad14; border-color: #faad14;" title="Chỉnh sửa">
                                    <i class="fa-regular fa-pen-to-square"></i> Sửa
                                </a>
                                <form action="{{ route('medicines.destroy', $item) }}" method="POST" style="margin: 0; display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thuốc {{ $item->name }} không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ant-btn ant-btn-sm ant-btn-danger" title="Xóa">
                                        <i class="fa-regular fa-trash-can"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 48px 0; color: rgba(0,0,0,0.45);">
                            <div style="font-size: 40px; color: #d9d9d9; margin-bottom: 12px;">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <div style="font-size: 15px; font-weight: 500; color: rgba(0,0,0,0.65);">Kho thuốc hiện đang trống</div>
                            <div style="font-size: 13px; margin-top: 4px;">Bấm nút <a href="{{ route('medicines.create') }}" style="color: #1677ff;">Thêm Thuốc Mới</a> để thêm sản phẩm đầu tiên.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Ant Design Style Pagination Footer -->
    @if($medicines->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div style="font-size: 13px; color: rgba(0,0,0,0.45);">
                Hiển thị <strong>{{ $medicines->firstItem() }}</strong> - <strong>{{ $medicines->lastItem() }}</strong> trên tổng <strong>{{ $medicines->total() }}</strong> thuốc
            </div>
            <div>
                <ul class="ant-pagination" style="margin: 0; padding: 0; display: flex; list-style: none; gap: 6px;">
                    {{-- Previous Page Link --}}
                    @if ($medicines->onFirstPage())
                        <li class="ant-pagination-prev ant-pagination-disabled"><span class="ant-btn ant-btn-sm" style="color: rgba(0,0,0,0.25); cursor: not-allowed;"><i class="fa-solid fa-chevron-left"></i></span></li>
                    @else
                        <li class="ant-pagination-prev"><a href="{{ $medicines->previousPageUrl() }}" class="ant-btn ant-btn-sm"><i class="fa-solid fa-chevron-left"></i></a></li>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach(range(1, $medicines->lastPage()) as $page)
                        @if ($page == $medicines->currentPage())
                            <li class="ant-pagination-item ant-pagination-item-active"><span class="ant-btn ant-btn-sm ant-btn-primary">{{ $page }}</span></li>
                        @else
                            <li class="ant-pagination-item"><a href="{{ $medicines->url($page) }}" class="ant-btn ant-btn-sm">{{ $page }}</a></li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($medicines->hasMorePages())
                        <li class="ant-pagination-next"><a href="{{ $medicines->nextPageUrl() }}" class="ant-btn ant-btn-sm"><i class="fa-solid fa-chevron-right"></i></a></li>
                    @else
                        <li class="ant-pagination-next ant-pagination-disabled"><span class="ant-btn ant-btn-sm" style="color: rgba(0,0,0,0.25); cursor: not-allowed;"><i class="fa-solid fa-chevron-right"></i></span></li>
                    @endif
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection