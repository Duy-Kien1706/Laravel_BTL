<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\User;
use App\Models\WarehouseReceipt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyManagementTest extends TestCase
{
    protected User $admin;
    protected Category $category;
    protected Medicine $medicine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản Trị Viên',
                'password' => bcrypt('12345678'),
                'phone' => '0912345678',
                'role' => 'admin',
                'status' => 'active',
                'salary' => 15000000,
            ]
        );

        $this->category = Category::firstOrCreate(
            ['name' => 'Thuốc Kháng Sinh'],
            ['description' => 'Các loại kháng sinh thông dụng']
        );

        $this->medicine = Medicine::firstOrCreate(
            ['code' => 'TEST-MED-01'],
            [
                'name' => 'Amoxicillin 500mg Test',
                'category_id' => $this->category->id,
                'unit' => 'Hộp',
                'price' => 50000,
                'stock' => 100,
                'expiry_date' => now()->addMonths(6),
                'description' => 'Dùng để test tính năng hệ thống',
            ]
        );
    }

    /**
     * Test Dashboard truy cập thành công
     */
    public function test_dashboard_can_be_accessed(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Bảng Điều Khiển Tổng Quan');
        $response->assertSee('Doanh Thu Hôm Nay');
    }

    /**
     * Test Quản lý Nhân Viên: xem danh sách và tạo nhân viên mới
     */
    public function test_staff_management(): void
    {
        $response = $this->actingAs($this->admin)->get(route('staff.index'));
        $response->assertStatus(200);

        $uniqueEmail = 'staff_' . uniqid() . '@pharmacare.vn';
        $createResponse = $this->actingAs($this->admin)->post(route('staff.store'), [
            'name' => 'Dược Sĩ Test',
            'email' => $uniqueEmail,
            'phone' => '0987654321',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => 'pharmacist',
            'status' => 'active',
            'salary' => 8000000,
        ]);

        $createResponse->assertRedirect(route('staff.index'));
        $this->assertDatabaseHas('users', ['email' => $uniqueEmail]);
    }

    /**
     * Test Quản lý Kho: Nhập kho làm tăng tồn kho thuốc
     */
    public function test_warehouse_import_increases_stock(): void
    {
        $initialStock = $this->medicine->fresh()->stock;
        $importQty = 25;

        $response = $this->actingAs($this->admin)->post(route('warehouse.store'), [
            'type' => 'import',
            'supplier' => 'Nhà Cung Cấp Dược Phẩm Trung Ương',
            'note' => 'Nhập thử nghiệm',
            'items' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => $importQty,
                    'unit_price' => 45000,
                ]
            ]
        ]);

        $response->assertRedirect();
        $this->assertEquals($initialStock + $importQty, $this->medicine->fresh()->stock);
    }

    /**
     * Test Quản lý Hóa Đơn: Tạo hóa đơn làm giảm tồn kho và hủy hóa đơn phục hồi tồn kho
     */
    public function test_invoice_creation_deducts_stock_and_cancellation_restores(): void
    {
        $initialStock = $this->medicine->fresh()->stock;
        $saleQty = 5;

        // 1. Tạo hóa đơn bán hàng
        $createResponse = $this->actingAs($this->admin)->post(route('invoices.store'), [
            'customer_name' => 'Khách Mua Test',
            'customer_phone' => '0901234567',
            'payment_method' => 'cash',
            'note' => 'Uống 2 viên/ngày sau ăn',
            'items' => [
                [
                    'medicine_id' => $this->medicine->id,
                    'quantity' => $saleQty,
                    'unit_price' => 50000,
                ]
            ]
        ]);

        $createResponse->assertRedirect();
        $this->assertEquals($initialStock - $saleQty, $this->medicine->fresh()->stock);

        $invoice = Invoice::where('customer_name', 'Khách Mua Test')->latest()->first();
        $this->assertNotNull($invoice);
        $this->assertEquals('paid', $invoice->status);

        // 2. Hủy hóa đơn bán hàng
        $cancelResponse = $this->actingAs($this->admin)->post(route('invoices.cancel', $invoice));
        $cancelResponse->assertRedirect(route('invoices.show', $invoice));

        $this->assertEquals('cancelled', $invoice->fresh()->status);
        $this->assertEquals($initialStock, $this->medicine->fresh()->stock);
    }
}
