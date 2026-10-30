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
            'expiring_soon' => Medicine::whereBetween('expiry_date', [Carbon::today(), Carbon::today()->addDays(30)])->count(),
            'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
            'today_revenue' => Invoice::where('status', 'paid')->whereDate('created_at', Carbon::today())->sum('total_amount'),
            'total_invoices' => Invoice::where('status', 'paid')->count(),
            'total_warehouse_receipts' => WarehouseReceipt::count(),
        ];

        return view('dashboard', compact('stats'));
    }
}
