<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Hiển thị danh sách thuốc kèm Tìm kiếm và Lọc
     */
    public function index(Request $request)
    {
        $query = Medicine::with('category');

        // Tìm kiếm theo tên hoặc mã thuốc
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo tình trạng kho
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->where('stock', '<=', 10)->where('stock', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock', '<=', 0);
            }
        }

        // Lọc theo tình trạng hạn sử dụng
        if ($request->filled('expiry_status')) {
            if ($request->expiry_status === 'expired') {
                $query->where('expiry_date', '<', Carbon::today());
            } elseif ($request->expiry_status === 'expiring') {
                $query->whereBetween('expiry_date', [Carbon::today(), Carbon::today()->addDays(30)]);
            }
        }

        $medicines = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('medicines.index', compact('medicines', 'categories'));
    }

    /**
     * Form thêm thuốc mới
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $defaultUnits = ['Viên', 'Vỉ', 'Hộp', 'Chai', 'Gói', 'Tuýp', 'Ống', 'Lọ'];

        return view('medicines.create', compact('categories', 'defaultUnits'));
    }

    /**
     * Lưu thuốc mới vào CSDL
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:medicines,code'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'expiry_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên thuốc.',
            'code.required' => 'Vui lòng nhập mã thuốc.',
            'code.unique' => 'Mã thuốc này đã tồn tại trong hệ thống.',
            'category_id.required' => 'Vui lòng chọn danh mục thuốc.',
            'category_id.exists' => 'Danh mục thuốc không hợp lệ.',
            'unit.required' => 'Vui lòng chọn hoặc nhập đơn vị tính.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'price.numeric' => 'Giá bán phải là số hợp lệ.',
            'price.min' => 'Giá bán không được nhỏ hơn 0.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min' => 'Số lượng tồn kho không được âm.',
            'expiry_date.required' => 'Vui lòng chọn hạn sử dụng.',
            'expiry_date.date' => 'Hạn sử dụng không đúng định dạng ngày tháng.',
        ]);

        Medicine::create($validated);

        return redirect()->route('medicines.index')
            ->with('success', 'Thêm thuốc "' . $validated['name'] . '" vào kho thành công!');
    }

    /**
     * Xem thông tin chi tiết một loại thuốc
     */
    public function show(Medicine $medicine)
    {
        $medicine->load('category');
        return view('medicines.show', compact('medicine'));
    }

    /**
     * Form chỉnh sửa thông tin thuốc
     */
    public function edit(Medicine $medicine)
    {
        $categories = Category::orderBy('name')->get();
        $defaultUnits = ['Viên', 'Vỉ', 'Hộp', 'Chai', 'Gói', 'Tuýp', 'Ống', 'Lọ'];

        return view('medicines.edit', compact('medicine', 'categories', 'defaultUnits'));
    }

    /**
     * Cập nhật thông tin thuốc
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:medicines,code,' . $medicine->id],
            'category_id' => ['required', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'expiry_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên thuốc.',
            'code.required' => 'Vui lòng nhập mã thuốc.',
            'code.unique' => 'Mã thuốc này đã thuộc về thuốc khác.',
            'category_id.required' => 'Vui lòng chọn danh mục thuốc.',
            'category_id.exists' => 'Danh mục thuốc không hợp lệ.',
            'unit.required' => 'Vui lòng chọn hoặc nhập đơn vị tính.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'price.numeric' => 'Giá bán phải là số hợp lệ.',
            'price.min' => 'Giá bán không được nhỏ hơn 0.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock.integer' => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min' => 'Số lượng tồn kho không được âm.',
            'expiry_date.required' => 'Vui lòng chọn hạn sử dụng.',
            'expiry_date.date' => 'Hạn sử dụng không đúng định dạng ngày tháng.',
        ]);

        $medicine->update($validated);

        return redirect()->route('medicines.index')
            ->with('success', 'Cập nhật thông tin thuốc "' . $medicine->name . '" thành công!');
    }

    /**
     * Xóa thuốc khỏi CSDL
     */
    public function destroy(Medicine $medicine)
    {
        $name = $medicine->name;
        $medicine->delete();

        return redirect()->route('medicines.index')
            ->with('success', 'Đã xóa thuốc "' . $name . '" khỏi kho dữ liệu thành công!');
    }
}
