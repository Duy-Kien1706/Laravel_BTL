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

            <!-- Card danh sách thuốc nhập/xuất -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-list-check text-primary me-2"></i> Danh Sách Thuốc</h5>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addRowBtn">
                        <i class="bi bi-plus-lg me-1"></i> Thêm Thuốc
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" id="itemsTable">
                            <thead class="table-light small text-uppercase text-secondary">
                                <tr>
                                    <th style="min-width: 250px;">Tên Thuốc & Nhóm</th>
                                    <th style="width: 140px;" class="text-center">Số Lượng</th>
                                    <th style="width: 180px;" class="text-end">Đơn Giá (VNĐ)</th>
                                    <th style="width: 180px;" class="text-end">Thành Tiền</th>
                                    <th style="width: 60px;" class="text-center">Xóa</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr class="item-row">
                                    <td>
                                        <select name="items[0][medicine_id]" class="form-select form-select-sm med-select" required>
                                            <option value="">-- Chọn thuốc --</option>
                                            @foreach($medicines as $med)
                                                <option value="{{ $med->id }}" data-price="{{ (int)$med->price }}" data-stock="{{ $med->stock }}" data-unit="{{ $med->unit }}">
                                                    {{ $med->name }} (Mã: {{ $med->code }} - Tồn: {{ $med->stock }} {{ $med->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][quantity]" class="form-control form-control-sm text-center qty-input" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[0][unit_price]" class="form-control form-control-sm text-end price-input" value="0" min="0" step="500" required>
                                    </td>
                                    <td class="text-end fw-bold text-primary line-total">
                                        0 VNĐ
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold fs-6">Tổng Giá Trị Phiếu Kho:</td>
                                    <td class="text-end fw-bold text-success fs-5" id="grandTotal">0 VNĐ</td>
                                    <td></td>
                                </tr>
                            </tfoot>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let rowIndex = 1;
        const itemsBody = document.getElementById('itemsBody');
        const addRowBtn = document.getElementById('addRowBtn');
        const grandTotalElem = document.getElementById('grandTotal');

        function formatVND(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' VNĐ';
        }

        function calculateTotals() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const lineTotal = qty * price;
                row.querySelector('.line-total').textContent = formatVND(lineTotal);
                total += lineTotal;
            });
            grandTotalElem.textContent = formatVND(total);
        }

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                row.querySelector('.remove-row-btn').disabled = rows.length === 1;
            });
        }

        function bindRowEvents(row) {
            const select = row.querySelector('.med-select');
            const qtyInput = row.querySelector('.qty-input');
            const priceInput = row.querySelector('.price-input');
            const removeBtn = row.querySelector('.remove-row-btn');

            select.addEventListener('change', function () {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.dataset.price) {
                    priceInput.value = selectedOption.dataset.price;
                }
                calculateTotals();
            });

            qtyInput.addEventListener('input', calculateTotals);
            priceInput.addEventListener('input', calculateTotals);

            removeBtn.addEventListener('click', function () {
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    updateRemoveButtons();
                    calculateTotals();
                }
            });
        }

        bindRowEvents(document.querySelector('.item-row'));

        addRowBtn.addEventListener('click', function () {
            const firstRow = document.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelector('.med-select').name = `items[${rowIndex}][medicine_id]`;
            newRow.querySelector('.med-select').value = '';
            newRow.querySelector('.qty-input').name = `items[${rowIndex}][quantity]`;
            newRow.querySelector('.qty-input').value = 1;
            newRow.querySelector('.price-input').name = `items[${rowIndex}][unit_price]`;
            newRow.querySelector('.price-input').value = 0;
            newRow.querySelector('.line-total').textContent = '0 VNĐ';

            itemsBody.appendChild(newRow);
            bindRowEvents(newRow);
            updateRemoveButtons();
            rowIndex++;
        });
    });
</script>
@endsection
