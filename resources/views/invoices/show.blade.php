@extends('layouts.app')

@section('title', 'Chi Tiết Hóa Đơn - ' . $invoice->code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Top Action Bar (ẩn khi in) -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 no-print">
            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay Lại Danh Sách
            </a>
        </div>

        <!-- Hóa đơn bán lẻ chi tiết -->
        <div class="card border-0 shadow-sm p-4 p-md-5">
            <!-- Header hóa đơn -->
            <div class="row pb-4 mb-3 border-bottom align-items-center">
                <div class="col-sm-7">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="bg-primary text-white p-2 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="bi bi-capsule-pill fs-5"></i>
                        </span>
                        <div>
                            <h4 class="fw-bold mb-0 text-dark">NHÀ THUỐC PHARMACARE</h4>
                            <small class="text-muted">Đạt Chuẩn Thực Hành Tốt Phân Phối Thuốc GPP</small>
                        </div>
                    </div>
                    <div class="text-muted small">
                        <div><i class="bi bi-geo-alt me-1"></i> Số 123 Đường Y Dược, TP. Hà Nội</div>
                        <div><i class="bi bi-telephone me-1"></i> Hotline: 0988 123 456 - Web: pharmacare.vn</div>
                    </div>
                </div>

                <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                    <h3 class="fw-bold text-uppercase text-primary mb-1">HÓA ĐƠN BÁN LẺ</h3>
                    <div class="font-monospace fs-5 fw-bold text-secondary mb-1">#{{ $invoice->code }}</div>
                    <div class="small text-muted">{{ $invoice->created_at->format('d/m/Y H:i') }}</div>
                    <div class="mt-2">
                        @if($invoice->status === 'paid')
                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-1">
                                <i class="bi bi-check2-circle me-1"></i> ĐÃ THANH TOÁN
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle px-3 py-1">
                                <i class="bi bi-x-circle me-1"></i> ĐÃ HỦY ĐƠN HÀNG
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thông tin khách hàng & thu ngân -->
            <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">Khách hàng:</span>
                    <strong class="text-dark fs-6">{{ $invoice->customer_name }}</strong>
                </div>

                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">Số điện thoại:</span>
                    <span class="text-dark">{{ $invoice->customer_phone ?: 'Không có' }}</span>
                </div>

                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">Dược sĩ / Thu ngân:</span>
                    <strong class="text-dark">{{ $invoice->user->name ?? 'N/A' }}</strong>
                </div>

                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">Hình thức thanh toán:</span>
                    <span class="badge bg-light text-dark border mt-1">
                        {{ $invoice->payment_method_name }}
                    </span>
                </div>

                @if($invoice->note)
                    <div class="col-12 pt-2 border-top">
                        <span class="text-muted small d-block">Lời dặn / Ghi chú đơn:</span>
                        <span class="text-dark fst-italic">{{ $invoice->note }}</span>
                    </div>
                @endif
            </div>

            <!-- Bảng danh sách thuốc trong đơn -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light small text-uppercase text-secondary">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Tên Thuốc & Quy Cách</th>
                            <th class="text-center" style="width: 90px;">ĐVT</th>
                            <th class="text-center" style="width: 100px;">Số Lượng</th>
                            <th class="text-end" style="width: 140px;">Đơn Giá</th>
                            <th class="text-end" style="width: 160px;">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->details as $index => $detail)
                            <tr>
                                <td class="text-center fw-medium text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $detail->medicine->name ?? 'Thuốc đã xóa' }}</div>
                                    <small class="text-muted font-monospace">Mã: {{ $detail->medicine->code ?? 'N/A' }}</small>
                                </td>
                                <td class="text-center text-muted">{{ $detail->medicine->unit ?? '-' }}</td>
                                <td class="text-center fw-bold">{{ number_format($detail->quantity) }}</td>
                                <td class="text-end text-muted">{{ $detail->formatted_unit_price }}</td>
                                <td class="text-end fw-bold text-success">{{ $detail->formatted_total_price }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Không có chi tiết sản phẩm</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-group-divider">
                        <tr>
                            <td colspan="3" class="text-end fw-bold text-uppercase py-3">Tổng số lượng thuốc:</td>
                            <td class="text-center fw-bold py-3 fs-6">
                                {{ number_format($invoice->details->sum('quantity')) }}
                            </td>
                            <td class="text-end fw-bold text-uppercase py-3 fs-6">Tổng Thanh Toán:</td>
                            <td class="text-end fw-bold text-success py-3 fs-5">
                                {{ $invoice->formatted_total_amount }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Footer lời cảm ơn & chữ ký -->
            <div class="text-center my-4">
                <p class="fst-italic text-muted mb-1">Cảm ơn quý khách đã tin tưởng PharmaCare!</p>
                <small class="text-muted">Quý khách vui lòng kiểm tra thuốc, liều dùng và hóa đơn trước khi rời quầy.</small>
            </div>

            <div class="row text-center mt-4 pt-2">
                <div class="col-6">
                    <p class="fw-bold mb-1">Khách Hàng</p>
                    <small class="text-muted">(Ký xác nhận)</small>
                    <div style="height: 60px;"></div>
                    <p class="text-muted mb-0">{{ $invoice->customer_name }}</p>
                </div>
                <div class="col-6">
                    <p class="fw-bold mb-1">Dược Sĩ Bán Thuốc</p>
                    <small class="text-muted">(Ký và ghi rõ họ tên)</small>
                    <div style="height: 60px;"></div>
                    <p class="fw-semibold text-dark mb-0">{{ $invoice->user->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
