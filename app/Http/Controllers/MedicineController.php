<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Hiển thị danh sách thuốc (CRUD - Read)
     */
    public function index()
    {
        $medicines = Medicine::orderBy('created_at', 'desc')->paginate(10);

        return view('medicines.index', compact('medicines'));
    }

    /**
     * Hiển thị form thêm mới thuốc
     */
    public function create()
    {
        $defaultCategories = [
            'Kháng sinh',
            'Giảm đau - Hạ sốt',
            'Chống viêm',
            'Tiêu hóa - Dạ dày',
            'Hô hấp - Trị ho',
            'Vitamin & Khoáng chất',
            'Tim mạch & Huyết áp',
            'Da liễu - Dị ứng',
            'Thần kinh',
            'Khác',
        ];

        $defaultUnits = ['Viên', 'Vỉ', 'Hộp', 'Chai', 'Gói', 'Tuýp', 'Ống'];

        return view('medicines.create', compact('defaultCategories', 'defaultUnits'));
    }

    /**
     * Lưu thuốc mới vào CSDL
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:medicines,code'],
            'category' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'expiry_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên thuốc.',
            'code.required' => 'Vui lòng nhập mã thuốc.',
            'code.unique' => 'Mã thuốc này đã tồn tại trong hệ thống.',
            'category.required' => 'Vui lòng chọn hoặc nhập nhóm thuốc.',
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
        return view('medicines.show', compact('medicine'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin thuốc
     */
    public function edit(Medicine $medicine)
    {
        $defaultCategories = [
            'Kháng sinh',
            'Giảm đau - Hạ sốt',
            'Chống viêm',
            'Tiêu hóa - Dạ dày',
            'Hô hấp - Trị ho',
            'Vitamin & Khoáng chất',
            'Tim mạch & Huyết áp',
            'Da liễu - Dị ứng',
            'Thần kinh',
            'Khác',
        ];

        $defaultUnits = ['Viên', 'Vỉ', 'Hộp', 'Chai', 'Gói', 'Tuýp', 'Ống'];

        return view('medicines.edit', compact('medicine', 'defaultCategories', 'defaultUnits'));
    }

    /**
     * Cập nhật thông tin thuốc
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:medicines,code,' . $medicine->id],
            'category' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'expiry_date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Vui lòng nhập tên thuốc.',
            'code.required' => 'Vui lòng nhập mã thuốc.',
            'code.unique' => 'Mã thuốc này đã thuộc về thuốc khác.',
            'category.required' => 'Vui lòng chọn hoặc nhập nhóm thuốc.',
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
