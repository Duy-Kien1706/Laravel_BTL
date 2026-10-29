<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\User;
use App\Models\WarehouseReceipt;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Hiển thị bảng điều khiển tổng quan (Dashboard)
     */
    public function index()
    {
        $stats = [
            'total_medicines' => Medicine::count(),
            'total_categories' => Category::count(),
            'total_staff' => User::count(),
            'total_stock' => Medicine::sum('stock'),
            'low_stock' => Medicine::where('stock', '<=', 10)->count(),
            'expiring_soon' => Medicine::where('expiry_date', '<=', Carbon::now()->addDays(30))->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'today_revenue' => Invoice::where('status', 'paid')->whereDate('created_at', Carbon::today())->sum('total_amount'),
            'total_invoices' => Invoice::where('status', 'paid')->count(),
            'total_warehouse_receipts' => WarehouseReceipt::count(),
        ];

        // 5 hóa đơn bán hàng gần nhất
        $recentInvoices = Invoice::with('user')->latest()->take(5)->get();

        // 5 phiếu nhập/xuất kho gần nhất
        $recentReceipts = WarehouseReceipt::with('user')->latest()->take(5)->get();

        // 5 loại thuốc mới nhập gần nhất
        $recentMedicines = Medicine::with('category')->latest()->take(5)->get();

        // Phân bổ thuốc theo danh mục
        $categoryBreakdown = Category::withCount('medicines')->orderBy('medicines_count', 'desc')->get();

        return view('dashboard', compact('stats', 'recentInvoices', 'recentReceipts', 'recentMedicines', 'categoryBreakdown'));
    }
}
