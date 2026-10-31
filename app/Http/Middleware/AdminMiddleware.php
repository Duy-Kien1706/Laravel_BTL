<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập trang quản lý thuốc!');
        }

        // Kiểm tra quyền Admin nếu route yêu cầu 'admin:only'
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($role === 'only' && !$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Chỉ Quản trị viên (Admin) mới có quyền quản lý nhà cung cấp và nhân viên!');
        }

        return $next($request);
    }
}
