<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Danh sách hóa đơn bán hàng
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['user', 'details']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Form lập hóa đơn bán hàng mới
     */
    public function create(Request $request)
    {
        $medicines = Medicine::with('category')->where('stock', '>', 0)->orderBy('name')->get();
        $rowCount = max(1, min(15, (int) $request->query('rows', 3)));

        return view('invoices.create', compact('medicines', 'rowCount'));
    }

    /**
     * Lưu hóa đơn và tự động trừ kho thuốc
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
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cash,transfer,card'],
            'note' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicine_id' => ['required', 'exists:medicines,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
        ], [
            'customer_name.required' => 'Vui lòng nhập tên khách hàng.',
            'items.required' => 'Vui lòng chọn ít nhất một loại thuốc để bán.',
            'items.min' => 'Vui lòng chọn ít nhất một loại thuốc để bán.',
            'items.*.quantity.min' => 'Số lượng bán phải lớn hơn 0.',
        ]);

        try {
            DB::beginTransaction();

            $code = 'HD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $invoice = Invoice::create([
                'code' => $code,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'] ?? null,
                'user_id' => Auth::id(),
                'total_amount' => 0,
                'payment_method' => $validated['payment_method'],
                'status' => 'paid',
                'note' => $validated['note'] ?? null,
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                $quantity = (int) $item['quantity'];
                // Nếu không nhập đơn giá hoặc để 0 thì tự động lấy giá bán niêm yết của thuốc
                $unitPrice = (!empty($item['unit_price']) && $item['unit_price'] > 0)
                    ? (float) $item['unit_price']
                    : (float) $medicine->price;
                $lineTotal = $quantity * $unitPrice;
                $totalAmount += $lineTotal;

                if ($medicine->stock < $quantity) {
                    throw new \Exception("Thuốc '{$medicine->name}' trong kho chỉ còn {$medicine->stock} {$medicine->unit}, không đủ để bán {$quantity}!");
                }

                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'medicine_id' => $medicine->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ]);

                // Tự động trừ kho thuốc khi bán
                $medicine->decrement('stock', $quantity);
            }

            $invoice->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Tạo hóa đơn bán thuốc ' . $invoice->code . ' thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi khi tạo hóa đơn: ' . $e->getMessage());
        }
    }

    /**
     * Xem chi tiết hóa đơn (kèm xem trước bản in)
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['user', 'details.medicine.category']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Hủy hóa đơn và hoàn trả số lượng vào kho
     */
    public function cancel(Invoice $invoice)
    {
        if ($invoice->status === 'cancelled') {
            return back()->with('error', 'Hóa đơn này đã được hủy trước đó!');
        }

        try {
            DB::beginTransaction();

            $invoice->update(['status' => 'cancelled']);

            // Hoàn trả số lượng tồn kho cho từng loại thuốc
            foreach ($invoice->details as $detail) {
                $medicine = $detail->medicine;
                if ($medicine) {
                    $medicine->increment('stock', $detail->quantity);
                }
            }

            DB::commit();

            return back()
                ->with('success', 'Đã hủy hóa đơn ' . $invoice->code . ' và hoàn trả tồn kho thuốc thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Lỗi khi hủy hóa đơn: ' . $e->getMessage());
        }
    }
}
