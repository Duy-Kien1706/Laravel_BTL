<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Danh sách nhân viên
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staffs = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('staff.index', compact('staffs'));
    }

    /**
     * Form thêm nhân viên
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Lưu nhân viên mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,pharmacist,warehouse'],
            'status' => ['required', 'in:active,inactive'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên nhân viên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'role.required' => 'Vui lòng chọn chức vụ.',
            'password.required' => 'Vui lòng nhập mật khẩu khởi tạo.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'salary' => $validated['salary'] ?? 0,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('staff.index')
            ->with('success', 'Thêm nhân viên "' . $validated['name'] . '" thành công!');
    }

    /**
     * Form chỉnh sửa nhân viên
     */
    public function edit(User $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    /**
     * Cập nhật thông tin nhân viên
     */
    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $staff->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,pharmacist,warehouse'],
            'status' => ['required', 'in:active,inactive'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên nhân viên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'role.required' => 'Vui lòng chọn chức vụ.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'salary' => $validated['salary'] ?? 0,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $staff->update($updateData);

        return redirect()->route('staff.index')
            ->with('success', 'Cập nhật thông tin nhân viên "' . $staff->name . '" thành công!');
    }

    /**
     * Xóa nhân viên
     */
    public function destroy(User $staff)
    {
        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')
                ->with('error', 'Bạn không thể tự xóa tài khoản đang đăng nhập!');
        }

        $name = $staff->name;
        $staff->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Đã xóa nhân viên "' . $name . '" thành công!');
    }
}
