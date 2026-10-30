<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Danh sách danh mục thuốc
     */
    public function index(Request $request)
    {
        $query = Category::withCount('medicines');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    /**
     * Form tạo danh mục mới
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Lưu danh mục mới vào CSDL
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Thêm danh mục "' . $validated['name'] . '" thành công!');
    }

    /**
     * Form chỉnh sửa danh mục
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Cập nhật thông tin danh mục
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Cập nhật danh mục "' . $category->name . '" thành công!');
    }

    /**
     * Xóa danh mục
     */
    public function destroy(Category $category)
    {
        $medicineCount = $category->medicines()->count();

        if ($medicineCount > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Không thể xóa danh mục "' . $category->name . '" vì đang có ' . $medicineCount . ' loại thuốc thuộc danh mục này!');
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Đã xóa danh mục "' . $name . '" thành công!');
    }
}
