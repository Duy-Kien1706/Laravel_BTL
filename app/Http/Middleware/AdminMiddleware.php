<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập trang quản lý thuốc!');
        }

        // Kiểm tra quyền Admin nếu route yêu cầu 'admin:only'
        if ($role === 'only' && !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Chỉ Quản trị viên (Admin) mới có quyền truy cập mục Bán Hàng & Nhân Sự!');
        }

        return $next($request);
    }
}
