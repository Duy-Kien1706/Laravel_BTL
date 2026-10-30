@extends('layouts.app')

@section('title', 'Lập Phiếu Kho Mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <a href="{{ route('warehouse.index') }}" class="btn btn-outline-secondary btn-sm mb-1">
                    <i class="bi bi-arrow-left me-1"></i> Quay Lại Danh Sách Phiếu
                </a>
                <h4 class="fw-bold mb-0">Lập Phiếu Nhập / Xuất Kho</h4>
            </div>
        </div>

        <form action="{{ route('warehouse.store') }}" method="POST" id="warehouseForm">
            @csrf

            <!-- Card thông tin chung -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-info-circle text-primary me-2"></i> Thông Tin Phiếu Kho</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="type" class="form-label fw-semibold small">Loại Phiếu Kho <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="import" {{ old('type') == 'import' ? 'selected' : '' }}>Nhập kho (Tăng số lượng tồn)</option>
                                <option value="export" {{ old('type') == 'export' ? 'selected' : '' }}>Xuất kho (Giảm số lượng tồn)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="supplier" class="form-label fw-semibold small">Nhà Cung Cấp / Đối Tác Nhận</label>
                            <input type="text" class="form-control @error('supplier') is-invalid @enderror" id="supplier" name="supplier" value="{{ old('supplier') }}" placeholder="VD: Công ty Dược Hậu Giang, Kho trung chuyển...">
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="note" class="form-label fw-semibold small">Ghi Chú Phiếu Kho</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="2" placeholder="Nhập lý do nhập/xuất kho hoặc thông tin hợp đồng...">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card danh sách thuốc nhập/xuất (Xử lý hoàn toàn bằng PHP/Blade, không dùng JavaScript) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-list-check text-primary me-2"></i> Danh Sách Thuốc</h5>
                        <small class="text-muted">Các dòng không chọn thuốc sẽ tự động được bỏ qua. Nếu để trống đơn giá, hệ thống tự lấy giá của thuốc.</small>
                    </div>
                    <div class="d-flex gap-2">
                        @if($rowCount > 1)
                            <a href="{{ route('warehouse.create', ['rows' => $rowCount - 1]) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-dash-lg me-1"></i> Bớt Dòng
                            </a>
                        @endif
                        <a href="{{ route('warehouse.create', ['rows' => $rowCount + 1]) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Thêm Dòng ({{ $rowCount }})
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light small text-uppercase text-secondary">
                                <tr>
                                    <th class="text-center" style="width: 60px;">STT</th>
                                    <th style="min-width: 280px;">Tên Thuốc & Tồn Kho Hiện Tại</th>
                                    <th style="width: 150px;" class="text-center">Số Lượng</th>
                                    <th style="width: 200px;" class="text-end">Đơn Giá (VNĐ)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 0; $i < $rowCount; $i++)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $i + 1 }}</td>
                                        <td>
                                            <select name="items[{{ $i }}][medicine_id]" class="form-select form-select-sm" {{ $i === 0 ? 'required' : '' }}>
                                                <option value="">-- Chọn thuốc {{ $i === 0 ? '(Bắt buộc)' : '(Tùy chọn)' }} --</option>
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}" {{ old("items.$i.medicine_id") == $med->id ? 'selected' : '' }}>
                                                        {{ $med->name }} (Mã: {{ $med->code }} - Tồn: {{ $med->stock }} {{ $med->unit }}) — Giá: {{ number_format($med->price, 0, ',', '.') }} đ
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm text-center" value="{{ old("items.$i.quantity", 1) }}" min="1">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $i }}][unit_price]" class="form-control form-control-sm text-end" value="{{ old("items.$i.unit_price") }}" min="0" step="500" placeholder="Tự lấy giá niêm yết">
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-5">
                <a href="{{ route('warehouse.index') }}" class="btn btn-outline-secondary px-4">Hủy Bỏ</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check2-circle me-1"></i> Xác Nhận Lập Phiếu Kho
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
