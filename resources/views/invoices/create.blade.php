@extends('layouts.app')

@section('title', 'Lập Hóa Đơn Bán Thuốc Mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary btn-sm mb-1">
                    <i class="bi bi-arrow-left me-1"></i> Quay Lại Danh Sách Hóa Đơn
                </a>
                <h4 class="fw-bold mb-0">Lập Hóa Đơn Bán Thuốc</h4>
            </div>
        </div>

        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
            @csrf

            <!-- Thông tin khách hàng & thanh toán -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-lines-fill text-primary me-2"></i> Thông Tin Khách Hàng & Thanh Toán</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="customer_name" class="form-label fw-semibold small">Tên Khách Hàng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', 'Khách mua lẻ') }}" placeholder="VD: Nguyễn Văn A..." required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="customer_phone" class="form-label fw-semibold small">Số Điện Thoại</label>
                            <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="VD: 0988123456">
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="payment_method" class="form-label fw-semibold small">Phương Thức Thanh Toán <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                <option value="cash">Tiền mặt</option>
                                <option value="transfer">Chuyển khoản (QR / Internet Banking)</option>
                                <option value="card">Thẻ POS (ATM / Visa)</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="note" class="form-label fw-semibold small">Ghi Chú Đơn Hàng / Lời Dặn Bác Sĩ</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="2" placeholder="Ghi chú đơn thuốc hoặc liều dùng theo chỉ định...">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách thuốc bán (Xử lý hoàn toàn bằng PHP/Blade, không dùng JavaScript) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-check text-primary me-2"></i> Danh Sách Thuốc Bán</h5>
                        <small class="text-muted">Các dòng không chọn thuốc sẽ tự động được bỏ qua. Nếu để trống đơn giá, hệ thống tự lấy giá niêm yết của thuốc.</small>
                    </div>
                    <div class="d-flex gap-2">
                        @if($rowCount > 1)
                            <a href="{{ route('invoices.create', ['rows' => $rowCount - 1]) }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-dash-lg me-1"></i> Bớt Dòng
                            </a>
                        @endif
                        <a href="{{ route('invoices.create', ['rows' => $rowCount + 1]) }}" class="btn btn-outline-primary btn-sm">
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
                                    <th style="min-width: 280px;">Thuốc & Quy Cách (Giá Niêm Yết)</th>
                                    <th style="width: 150px;" class="text-center">Số Lượng Bán</th>
                                    <th style="width: 200px;" class="text-end">Đơn Giá Bán (VNĐ)</th>
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
                                                    <option value="{{ $med->id }}" >
                                                        {{ $med->name }} (Tồn: {{ $med->stock }} {{ $med->unit }}) — Giá: {{ number_format($med->price, 0, ',', '.') }} đ
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm text-center" value="" min="1">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $i }}][unit_price]" class="form-control form-control-sm text-end" value="" min="0" step="500" placeholder="Tự lấy giá niêm yết">
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Hành động Lưu -->
            <div class="d-flex justify-content-end gap-2 mb-5">
                <a href="{{ route('invoices.index') }}" class="btn btn-light border px-4">Hủy Bỏ</a>
                <button type="submit" class="btn btn-success px-4 d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-check2-circle fs-5"></i>
                    <span class="fw-semibold">Hoàn Tất & Tạo Hóa Đơn</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
