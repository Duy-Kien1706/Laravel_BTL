@extends('layouts.app')

@section('title', 'Chi Tiết Phiếu Kho - ' . $warehouse->code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Top Action Bar (ẩn khi in) -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('warehouse.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay Lại Danh Sách Phiếu
            </a>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> In Phiếu Kho
                </button>
                <a href="{{ route('warehouse.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Lập Phiếu Mới
                </a>
            </div>
        </div>

        <!-- Phiếu kho chi tiết -->
        <div class="card border-0 shadow-sm p-4">
            <!-- Header phiếu kho -->
            <div class="row pb-4 mb-4 border-bottom align-items-center">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="bg-primary text-white p-2 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="bi bi-capsule-pill fs-5"></i>
                        </span>
                        <div>
                            <h4 class="fw-bold mb-0 text-dark">NHÀ THUỐC PHARMACARE</h4>
                            <small class="text-muted">Hệ Thống Phân Phối Dược Phẩm Tiêu Chuẩn GPP</small>
                        </div>
                    </div>
                    <div class="text-muted small">
                        <div><i class="bi bi-geo-alt me-1"></i> Địa chỉ: Số 123 Đường Y Dược, TP. Hà Nội</div>
                        <div><i class="bi bi-telephone me-1"></i> Điện thoại: 0988 123 456 - Email: contact@pharmacare.vn</div>
                    </div>
                </div>

                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <h3 class="fw-bold text-uppercase mb-1 {{ $warehouse->type === 'import' ? 'text-success' : 'text-warning' }}">
                        PHIẾU {{ $warehouse->type === 'import' ? 'NHẬP KHO' : 'XUẤT KHO' }}
                    </h3>
                    <div class="font-monospace fs-5 fw-bold text-secondary mb-1">#{{ $warehouse->code }}</div>
                    <div class="small text-muted">Ngày lập: {{ $warehouse->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <!-- Thông tin phiếu kho -->
            <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">Loại giao dịch:</span>
                    @if($warehouse->type === 'import')
                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle mt-1">
                            <i class="bi bi-arrow-down-left-square me-1"></i> Nhập kho
                        </span>
                    @else
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle mt-1">
                            <i class="bi bi-arrow-up-right-square me-1"></i> Xuất kho
                        </span>
                    @endif
                </div>

                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">Người thực hiện:</span>
                    <strong class="text-dark">{{ $warehouse->user->name ?? 'N/A' }}</strong>
                    <div class="small text-muted">({{ $warehouse->user->role_name ?? 'Nhân viên' }})</div>
                </div>

                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">Đối tác / Nhà cung cấp:</span>
                    <strong class="text-dark">{{ $warehouse->supplier ?: 'Không ghi nhận' }}</strong>
                </div>

                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">Ghi chú phiếu:</span>
                    <span class="text-dark">{{ $warehouse->note ?: 'Không có ghi chú' }}</span>
                </div>
            </div>

            <!-- Bảng danh sách mặt hàng -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light small text-uppercase text-secondary">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Mã & Tên Thuốc</th>
                            <th>Danh Mục</th>
                            <th class="text-center" style="width: 90px;">ĐVT</th>
                            <th class="text-center" style="width: 100px;">Số Lượng</th>
                            <th class="text-end" style="width: 140px;">Đơn Giá</th>
                            <th class="text-end" style="width: 160px;">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warehouse->details as $index => $detail)
                            <tr>
                                <td class="text-center fw-medium text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $detail->medicine->name ?? 'Thuốc đã xóa' }}</div>
                                    <small class="text-muted font-monospace">Mã: {{ $detail->medicine->code ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border">
                                        {{ $detail->medicine->category->name ?? 'Chưa phân loại' }}
                                    </span>
                                </td>
                                <td class="text-center text-muted">{{ $detail->medicine->unit ?? '-' }}</td>
                                <td class="text-center fw-bold">{{ number_format($detail->quantity) }}</td>
                                <td class="text-end text-muted">{{ $detail->formatted_unit_price }}</td>
                                <td class="text-end fw-bold text-primary">{{ $detail->formatted_total_price }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Không có chi tiết mặt hàng trong phiếu này</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td colspan="4" class="text-end fw-bold text-uppercase py-3">Tổng số lượng:</td>
                            <td class="text-center fw-bold py-3 fs-6">
                                {{ number_format($warehouse->details->sum('quantity')) }}
                            </td>
                            <td class="text-end fw-bold text-uppercase py-3">Tổng tiền:</td>
                            <td class="text-end fw-bold text-primary py-3 fs-5">
                                {{ $warehouse->formatted_total_amount }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Chữ ký xác nhận (hiển thị khi in và xem) -->
            <div class="row text-center mt-4 pt-3">
                <div class="col-4">
                    <p class="fw-bold mb-1">Người Lập Phiếu</p>
                    <small class="text-muted">(Ký và ghi rõ họ tên)</small>
                    <div style="height: 70px;"></div>
                    <p class="fw-semibold text-dark mb-0">{{ $warehouse->user->name ?? 'N/A' }}</p>
                </div>
                <div class="col-4">
                    <p class="fw-bold mb-1">Thủ Kho</p>
                    <small class="text-muted">(Ký và ghi rõ họ tên)</small>
                    <div style="height: 70px;"></div>
                    <p class="text-muted mb-0">....................................</p>
                </div>
                <div class="col-4">
                    <p class="fw-bold mb-1">{{ $warehouse->type === 'import' ? 'Người Giao Hàng' : 'Người Nhận Hàng' }}</p>
                    <small class="text-muted">(Ký và ghi rõ họ tên)</small>
                    <div style="height: 70px;"></div>
                    <p class="text-muted mb-0">{{ $warehouse->supplier ?: '....................................' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
