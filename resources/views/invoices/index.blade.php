@extends('layouts.app')

@section('title', 'Quản Lý Hóa Đơn Bán Hàng')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header py-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-receipt text-primary me-2"></i> Quản Lý Hóa Đơn Bán Hàng</h4>
                <small class="text-muted">Quản lý hóa đơn bán lẻ thuốc tại quầy, trạng thái thanh toán và in hóa đơn</small>
            </div>
            <div>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="bi bi-cart-plus"></i>
                    <span>Lập Hóa Đơn Mới</span>
                </a>
            </div>
        </div>

        <!-- Bộ lọc & Tìm kiếm hóa đơn -->
        <form action="{{ route('invoices.index') }}" method="GET" class="row g-2 mt-2 pt-3 border-top">
            <div class="col-md-4 col-lg-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Tìm mã HĐ, tên KH, SĐT..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3 col-lg-3">
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="">-- Hình thức thanh toán --</option>
                    <option value="cash">Tiền mặt</option>
                    <option value="transfer" >Chuyển khoản</option>
                    <option value="card" >Thẻ ngân hàng</option>
                </select>
            </div>

            <div class="col-md-3 col-lg-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Trạng thái đơn --</option>
                    <option value="paid" >Đã thanh toán</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>

            <div class="col-md-2 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i> Lọc
                </button>
                @if(request()->hasAny(['search', 'payment_method', 'status']))
                    <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-secondary" title="Xóa bộ lọc">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase small text-secondary">
                    <tr>
                        <th class="ps-3" style="width: 140px;">Mã Hóa Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Dược Sĩ / Thu Ngân</th>
                        <th class="text-center" style="width: 140px;">Phương Thức</th>
                        <th class="text-end" style="width: 150px;">Tổng Tiền</th>
                        <th class="text-center" style="width: 130px;">Trạng Thái</th>
                        <th class="text-center" style="width: 140px;">Thời Gian</th>
                        <th class="text-center pe-3" style="width: 130px;">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                        <tr>
                            <td class="ps-3">
                                <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace fs-6">
                                    {{ $inv->code }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $inv->customer_name }}</div>
                                @if($inv->customer_phone)
                                    <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $inv->customer_phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark"><i class="bi bi-person me-1"></i>{{ $inv->user->name ?? 'N/A' }}</div>
                            </td>
                            <td class="text-center">
                                @if($inv->payment_method === 'cash')
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                        <i class="bi bi-cash me-1"></i> Tiền mặt
                                    </span>
                                @elseif($inv->payment_method === 'transfer')
                                    <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                        <i class="bi bi-qr-code me-1"></i> Chuyển khoản
                                    </span>
                                @else
                                    <span class="badge bg-purple-subtle text-dark border">
                                        <i class="bi bi-credit-card me-1"></i> Thẻ
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-bold text-success">
                                {{ $inv->formatted_total_amount }}
                            </td>
                            <td class="text-center">
                                @if($inv->status === 'paid')
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                        <i class="bi bi-check2-circle me-1"></i> Thành công
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">
                                        <i class="bi bi-x-circle me-1"></i> Đã hủy
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                {{ $inv->created_at ? $inv->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('invoices.show', $inv) }}" class="btn btn-sm btn-outline-info shadow-sm" title="Xem chi tiết & In hóa đơn">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($inv->status === 'paid')
                                        <form action="{{ route('invoices.update', $inv) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn hủy hóa đơn {{ $inv->code }} không?\nSau khi hủy, số lượng thuốc sẽ được hoàn trả lại vào kho tự động.');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Hủy hóa đơn">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt-cutoff fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Không tìm thấy hóa đơn nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($invoices->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Hiển thị <strong>{{ $invoices->firstItem() }}</strong> - <strong>{{ $invoices->lastItem() }}</strong> trên tổng <strong>{{ $invoices->total() }}</strong> hóa đơn
                </div>
                <div>
                    {{ $invoices->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
