<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Danh sách nhà cung cấp dược phẩm
     */
    public function index(Request $request)
    {
        $query = Supplier::withCount('medicines');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Form thêm nhà cung cấp mới
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Lưu nhà cung cấp mới vào CSDL
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:suppliers,code'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'code.required' => 'Vui lòng nhập mã nhà cung cấp.',
            'code.unique' => 'Mã nhà cung cấp này đã tồn tại trong hệ thống.',
            'name.required' => 'Vui lòng nhập tên nhà cung cấp.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Thêm nhà cung cấp "' . $validated['name'] . '" thành công!');
    }

    /**
     * Form chỉnh sửa thông tin nhà cung cấp
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Cập nhật thông tin nhà cung cấp
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:suppliers,code,' . $supplier->id],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'code.required' => 'Vui lòng nhập mã nhà cung cấp.',
            'code.unique' => 'Mã nhà cung cấp này đã thuộc về đơn vị khác.',
            'name.required' => 'Vui lòng nhập tên nhà cung cấp.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Cập nhật nhà cung cấp "' . $supplier->name . '" thành công!');
    }

    /**
     * Xóa nhà cung cấp
     */
    public function destroy(Supplier $supplier)
    {
        $medicineCount = $supplier->medicines()->count();

        if ($medicineCount > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Không thể xóa nhà cung cấp "' . $supplier->name . '" vì đang cung cấp ' . $medicineCount . ' loại thuốc trong kho!');
        }

        $name = $supplier->name;
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Đã xóa nhà cung cấp "' . $name . '" thành công!');
    }
}
