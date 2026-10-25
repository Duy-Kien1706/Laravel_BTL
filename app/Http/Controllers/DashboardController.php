<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Hiển thị bảng điều khiển thống kê (Dashboard)
     */
    public function index()
    {
        $stats = [
            'total_medicines' => Medicine::count(),
            'total_categories' => Category::count(),
            'total_stock' => Medicine::sum('stock'),
            'low_stock' => Medicine::where('stock', '<=', 10)->count(),
            'expiring_soon' => Medicine::where('expiry_date', '<=', Carbon::now()->addDays(30))->count(),
        ];

        // 5 loại thuốc nhập mới nhất
        $recentMedicines = Medicine::with('category')->latest()->take(5)->get();

        // Thống kê phân bổ thuốc theo danh mục
        $categoryBreakdown = Category::withCount('medicines')->orderBy('medicines_count', 'desc')->get();

        return view('dashboard', compact('stats', 'recentMedicines', 'categoryBreakdown'));
    }
}
