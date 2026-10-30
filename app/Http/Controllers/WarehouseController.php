<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\WarehouseReceipt;
use App\Models\WarehouseReceiptDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    /**
     * Danh sách phiếu nhập / xuất kho
     */
    public function index(Request $request)
    {
        $query = WarehouseReceipt::with(['user', 'details']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $receipts = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('warehouse.index', compact('receipts'));
    }

    /**
     * Form lập phiếu nhập / xuất kho mới
     */
    public function create(Request $request)
    {
        $medicines = Medicine::with('category')->orderBy('name')->get();
        $rowCount = max(1, min(15, (int) $request->query('rows', 3)));

        return view('warehouse.create', compact('medicines', 'rowCount'));
    }

    /**
     * Lưu phiếu kho và tự động cập nhật tồn kho
     */
    public function store(Request $request)
    {
        // Lọc bỏ các dòng trống (chưa chọn thuốc) trước khi validate
        $filteredItems = collect($request->input('items', []))
            ->filter(fn ($item) => !empty($item['medicine_id']))
            ->values()
            ->all();
        $request->merge(['items' => $filteredItems]);

        $validated = $request->validate([
            'type' => ['required', 'in:import,export'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', 'exists:medicines,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
        ], [
            'type.required' => 'Vui lòng chọn loại phiếu kho.',
            'items.required' => 'Vui lòng thêm ít nhất một loại thuốc vào phiếu.',
            'items.min' => 'Vui lòng thêm ít nhất một loại thuốc vào phiếu.',
            'items.*.medicine_id.required' => 'Vui lòng chọn thuốc hợp lệ.',
            'items.*.quantity.min' => 'Số lượng thuốc phải lớn hơn 0.',
            'items.*.unit_price.min' => 'Đơn giá không được âm.',
        ]);

        try {
            DB::beginTransaction();

            $prefix = $validated['type'] === 'import' ? 'NK' : 'XK';
            $code = $prefix . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $receipt = WarehouseReceipt::create([
                'code' => $code,
                'type' => $validated['type'],
                'supplier' => $validated['supplier'] ?? null,
                'user_id' => auth()->id(),
                'total_amount' => 0,
                'note' => $validated['note'] ?? null,
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                $quantity = (int) $item['quantity'];
                // Nếu không nhập đơn giá hoặc để 0 thì tự động lấy giá niêm yết của thuốc
                $unitPrice = (!empty($item['unit_price']) && $item['unit_price'] > 0)
                    ? (float) $item['unit_price']
                    : (float) $medicine->price;
                $lineTotal = $quantity * $unitPrice;
                $totalAmount += $lineTotal;

                WarehouseReceiptDetail::create([
                    'warehouse_receipt_id' => $receipt->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ]);

                // Tự động điều chỉnh số lượng tồn kho
                if ($validated['type'] === 'import') {
                    $medicine->increment('stock', $quantity);
                } else {
                    if ($medicine->stock < $quantity) {
                        throw new \Exception("Thuốc '{$medicine->name}' trong kho chỉ còn {$medicine->stock} {$medicine->unit}, không đủ để xuất {$quantity}!");
                    }
                    $medicine->decrement('stock', $quantity);
                }
            }

            $receipt->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('warehouse.show', $receipt)
                ->with('success', 'Lập phiếu ' . $receipt->type_name . ' (' . $receipt->code . ') thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi khi lập phiếu kho: ' . $e->getMessage());
        }
    }

    /**
     * Xem chi tiết phiếu kho
     */
    public function show(WarehouseReceipt $warehouse)
    {
        $warehouse->load(['user', 'details.medicine.category']);

        return view('warehouse.show', compact('warehouse'));
    }
}
