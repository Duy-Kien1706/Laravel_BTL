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
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Chuyển khoản (QR / Internet Banking)</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Thẻ POS (ATM / Visa)</option>
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

            <!-- Danh sách thuốc bán -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-check text-primary me-2"></i> Danh Sách Thuốc Bán</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addRowBtn">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Thuốc
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" id="itemsTable">
                            <thead class="table-light small text-uppercase text-secondary">
                                <tr>
                                    <th style="min-width: 260px;">Thuốc & Quy Cách</th>
                                    <th style="width: 140px;" class="text-center">Số Lượng</th>
                                    <th style="width: 170px;" class="text-end">Đơn Giá (VNĐ)</th>
                                    <th style="width: 170px;" class="text-end">Thành Tiền</th>
                                    <th style="width: 60px;" class="text-center">Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][medicine_id]" class="form-select form-select-sm med-select" required>
                                            <option value="">-- Chọn thuốc cần bán --</option>
                                            @foreach($medicines as $med)
                                                <option value="{{ $med->id }}" data-price="{{ (int)$med->price }}" data-stock="{{ $med->stock }}" data-unit="{{ $med->unit }}">
                                                    {{ $med->name }} (Tồn: {{ $med->stock }} {{ $med->unit }}) - {{ number_format($med->price, 0, ',', '.') }} đ
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="small text-muted mt-1 stock-info"></div>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" class="form-control form-control-sm text-center qty-input" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" class="form-control form-control-sm text-end price-input" value="0" min="0" step="500" required>
                                    </td>
                                    <td class="text-end fw-bold text-success line-total">
                                        0 VNĐ
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-group-divider bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold text-uppercase py-3 fs-6">Tổng Tiền Thanh Toán:</td>
                                    <td class="text-end fw-bold text-success py-3 fs-5" id="grandTotal">0 VNĐ</td>
                                    <td></td>
                                </tr>
                            </tfoot>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const itemsBody = document.getElementById('itemsBody');
    const addRowBtn = document.getElementById('addRowBtn');
    const grandTotalEl = document.getElementById('grandTotal');
    let rowIndex = 1;

    function formatNumber(num) {
        return new Intl.NumberFormat('vi-VN').format(num) + ' VNĐ';
    }

    function updateLineTotal(row) {
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const lineTotalEl = row.querySelector('.line-total');

        const qty = parseFloat(qtyInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = qty * price;

        lineTotalEl.textContent = formatNumber(total);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let sum = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            sum += (qty * price);
        });
        grandTotalEl.textContent = formatNumber(sum);
    }

    function attachRowEvents(row) {
        const select = row.querySelector('.med-select');
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const removeBtn = row.querySelector('.remove-row-btn');
        const stockInfo = row.querySelector('.stock-info');

        select.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (opt.value) {
                const price = opt.getAttribute('data-price') || 0;
                const stock = opt.getAttribute('data-stock') || 0;
                const unit = opt.getAttribute('data-unit') || '';

                priceInput.value = price;
                qtyInput.max = stock;
                if (stockInfo) {
                    stockInfo.innerHTML = `<span class="badge bg-secondary-subtle text-secondary-emphasis">Còn ${stock} ${unit}</span>`;
                }
            } else {
                priceInput.value = 0;
                qtyInput.removeAttribute('max');
                if (stockInfo) stockInfo.innerHTML = '';
            }
            updateLineTotal(row);
        });

        qtyInput.addEventListener('input', function() {
            const max = parseInt(this.max);
            if (max && parseInt(this.value) > max) {
                alert(`Số lượng bán vượt quá tồn kho hiện có (${max})!`);
                this.value = max;
            }
            updateLineTotal(row);
        });

        priceInput.addEventListener('input', function() {
            updateLineTotal(row);
        });

        removeBtn.addEventListener('click', function () {
            if (document.querySelectorAll('.item-row').length > 1) {
                row.remove();
                calculateGrandTotal();
                updateRemoveButtons();
            }
        });
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(r => {
            const btn = r.querySelector('.remove-row-btn');
            btn.disabled = (rows.length === 1);
        });
    }

    addRowBtn.addEventListener('click', function () {
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        // Cập nhật names
        newRow.querySelector('.med-select').name = `items[${rowIndex}][medicine_id]`;
        newRow.querySelector('.med-select').value = '';
        newRow.querySelector('.qty-input').name = `items[${rowIndex}][quantity]`;
        newRow.querySelector('.qty-input').value = 1;
        newRow.querySelector('.qty-input').removeAttribute('max');
        newRow.querySelector('.price-input').name = `items[${rowIndex}][unit_price]`;
        newRow.querySelector('.price-input').value = 0;
        newRow.querySelector('.line-total').textContent = '0 VNĐ';

        const stockInfo = newRow.querySelector('.stock-info');
        if (stockInfo) stockInfo.innerHTML = '';

        itemsBody.appendChild(newRow);
        attachRowEvents(newRow);
        updateRemoveButtons();
        rowIndex++;
    });

    // Gán sự kiện cho hàng đầu tiên
    document.querySelectorAll('.item-row').forEach(row => {
        attachRowEvents(row);
    });
    updateRemoveButtons();
    calculateGrandTotal();
});
</script>
@endsection
