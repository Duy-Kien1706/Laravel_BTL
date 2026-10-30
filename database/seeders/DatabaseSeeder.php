<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo 3 tài khoản nhân viên với các vai trò khác nhau
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Nguyễn Văn Quản Lý (Admin)',
                'phone' => '0988123456',
                'role' => 'admin',
                'status' => 'active',
                'salary' => 18000000,
                'password' => Hash::make('12345678'),
            ]
        );

        $pharmacist = User::updateOrCreate(
            ['email' => 'duocsi@gmail.com'],
            [
                'name' => 'Trần Thị Thu Trang (Dược Sĩ)',
                'phone' => '0977234567',
                'role' => 'pharmacist',
                'status' => 'active',
                'salary' => 11000000,
                'password' => Hash::make('12345678'),
            ]
        );

        $warehouseStaff = User::updateOrCreate(
            ['email' => 'thukho@gmail.com'],
            [
                'name' => 'Lê Hoàng Minh (Thủ Kho)',
                'phone' => '0912345678',
                'role' => 'warehouse',
                'status' => 'active',
                'salary' => 9500000,
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Tạo các nhóm / danh mục thuốc
        $categoriesData = [
            [
                'name' => 'Giảm đau - Hạ sốt',
                'description' => 'Các loại thuốc giảm các cơn đau thông thường và hạ thân nhiệt khi bị sốt, cảm cúm.',
            ],
            [
                'name' => 'Kháng sinh',
                'description' => 'Thuốc tiêu diệt hoặc ức chế sự phát triển của vi khuẩn gây nhiễm khuẩn trong cơ thể.',
            ],
            [
                'name' => 'Tiêu hóa - Dạ dày',
                'description' => 'Thuốc hỗ trợ tiêu hóa, trào ngược dạ dày thực quản, tiêu chảy, đầy bụng, viêm loét.',
            ],
            [
                'name' => 'Hô hấp - Trị ho',
                'description' => 'Thuốc giảm ho, long đờm, thông mũi và điều trị các triệu chứng đường hô hấp trên.',
            ],
            [
                'name' => 'Vitamin & Khoáng chất',
                'description' => 'Bổ sung vi chất dinh dưỡng thiết yếu, nâng cao sức đề kháng và phục hồi sức khỏe.',
            ],
            [
                'name' => 'Tim mạch & Huyết áp',
                'description' => 'Thuốc kiểm soát huyết áp, nhịp tim và dự phòng các bệnh lý tim mạch mãn tính.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $category = Category::updateOrCreate(['name' => $cat['name']], $cat);
            $categories[$cat['name']] = $category->id;
        }

        // 3. Tạo danh sách Nhà Cung Cấp mẫu
        $suppliersData = [
            [
                'code' => 'NCC001',
                'name' => 'Công ty Cổ phần Dược Hậu Giang (DHG Pharma)',
                'phone' => '02923891433',
                'email' => 'dhgpharma@dhgpharma.com.vn',
                'address' => '288 Bis Nguyễn Văn Cừ, Q. Ninh Kiều, TP. Cần Thơ',
                'description' => 'Nhà cung cấp thuốc kháng sinh, giảm đau - hạ sốt đạt chuẩn GMP-WHO hàng đầu Việt Nam.',
            ],
            [
                'code' => 'NCC002',
                'name' => 'Công ty Cổ phần Traphaco',
                'phone' => '18006612',
                'email' => 'info@traphaco.com.vn',
                'address' => '75 Yên Ninh, Quận Ba Đình, TP. Hà Nội',
                'description' => 'Chuyên cung cấp đông dược, thảo dược tiêu hóa và các sản phẩm chăm sóc sức khỏe.',
            ],
            [
                'code' => 'NCC003',
                'name' => 'Công ty Cổ phần Dược phẩm Imexpharm',
                'phone' => '02773851941',
                'email' => 'imp@imexpharm.com',
                'address' => 'Số 4 Đường 30/4, Phường 1, TP. Cao Lãnh, Đồng Tháp',
                'description' => 'Chuyên cung cấp kháng sinh chất lượng cao đạt chuẩn EU-GMP và thuốc tim mạch.',
            ],
            [
                'code' => 'NCC004',
                'name' => 'Công ty TNHH Sanofi-Aventis Việt Nam',
                'phone' => '02838298526',
                'email' => 'contact.vn@sanofi.com',
                'address' => 'Lô I-8-2, Đường D8, Khu Công Nghệ Cao, TP. Thủ Đức, TP. HCM',
                'description' => 'Đối tác phân phối thuốc ngoại nhập, thuốc tiêu hóa và thực phẩm bổ sung vitamin.',
            ],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sup) {
            $supplier = Supplier::updateOrCreate(['code' => $sup['code']], $sup);
            $suppliers[$sup['code']] = $supplier->id;
        }

        // 4. Tạo danh sách các loại thuốc thực tế mẫu
        $sampleMedicines = [
            [
                'name' => 'Paracetamol 500mg',
                'code' => 'PARA500',
                'category_id' => $categories['Giảm đau - Hạ sốt'],
                'supplier_id' => $suppliers['NCC001'],
                'unit' => 'Hộp',
                'price' => 35000,
                'stock' => 120,
                'expiry_date' => Carbon::now()->addMonths(18),
                'description' => 'Thuốc giảm đau nhanh, hạ sốt cho người lớn và trẻ em trên 12 tuổi trong các trường hợp cảm cúm, đau đầu, đau răng.',
            ],
            [
                'name' => 'Panadol Extra Đỏ',
                'code' => 'PANEXT',
                'category_id' => $categories['Giảm đau - Hạ sốt'],
                'supplier_id' => $suppliers['NCC004'],
                'unit' => 'Hộp',
                'price' => 52000,
                'stock' => 85,
                'expiry_date' => Carbon::now()->addMonths(24),
                'description' => 'Chứa Paracetamol và Caffeine giúp giảm đau nhanh chóng các cơn đau dai dẳng, hạ sốt hiệu quả mà không gây buồn ngủ.',
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'code' => 'AMOX500',
                'category_id' => $categories['Kháng sinh'],
                'supplier_id' => $suppliers['NCC003'],
                'unit' => 'Vỉ',
                'price' => 28000,
                'stock' => 8,
                'expiry_date' => Carbon::now()->addMonths(12),
                'description' => 'Kháng sinh nhóm Penicillin, chỉ định điều trị nhiễm khuẩn đường hô hấp trên và dưới, nhiễm khuẩn đường tiết niệu.',
            ],
            [
                'name' => 'Augmentin 1g',
                'code' => 'AUG1G',
                'category_id' => $categories['Kháng sinh'],
                'supplier_id' => $suppliers['NCC003'],
                'unit' => 'Hộp',
                'price' => 185000,
                'stock' => 45,
                'expiry_date' => Carbon::now()->addDays(20),
                'description' => 'Kháng sinh phổ rộng kết hợp Amoxicillin và Acid Clavulanic điều trị viêm tai giữa, viêm xoang, viêm phổi, nhiễm trùng da.',
            ],
            [
                'name' => 'Berberin 100mg',
                'code' => 'BERB100',
                'category_id' => $categories['Tiêu hóa - Dạ dày'],
                'supplier_id' => $suppliers['NCC002'],
                'unit' => 'Lọ',
                'price' => 18000,
                'stock' => 200,
                'expiry_date' => Carbon::now()->addMonths(30),
                'description' => 'Chiết xuất từ thảo dược thiên nhiên, điều trị viêm ruột, tiêu chảy, kiết lỵ do vi khuẩn an toàn và hiệu quả.',
            ],
            [
                'name' => 'Smecta 3g',
                'code' => 'SMECTA3',
                'category_id' => $categories['Tiêu hóa - Dạ dày'],
                'supplier_id' => $suppliers['NCC004'],
                'unit' => 'Hộp',
                'price' => 115000,
                'stock' => 60,
                'expiry_date' => Carbon::now()->addMonths(15),
                'description' => 'Thuốc bột pha hỗn dịch uống điều trị triệu chứng tiêu chảy cấp ở người lớn và trẻ em kết hợp bù nước.',
            ],
            [
                'name' => 'Gaviscon Dual Action',
                'code' => 'GAVISD',
                'category_id' => $categories['Tiêu hóa - Dạ dày'],
                'supplier_id' => $suppliers['NCC004'],
                'unit' => 'Hộp',
                'price' => 145000,
                'stock' => 5,
                'expiry_date' => Carbon::now()->addMonths(8),
                'description' => 'Dạng gói hỗn dịch giảm nhanh các triệu chứng trào ngược dạ dày thực quản, ợ chua, khó tiêu do thừa acid.',
            ],
            [
                'name' => 'Decolgen Forte',
                'code' => 'DECOLF',
                'category_id' => $categories['Hô hấp - Trị ho'],
                'supplier_id' => $suppliers['NCC001'],
                'unit' => 'Vỉ',
                'price' => 15000,
                'stock' => 150,
                'expiry_date' => Carbon::now()->addMonths(20),
                'description' => 'Điều trị các triệu chứng cảm cúm thông thường: sốt, nhức đầu, sổ mũi, nghẹt mũi và hắt hơi nhiều.',
            ],
            [
                'name' => 'Eugica Đỏ viên nang mềm',
                'code' => 'EUGICARD',
                'category_id' => $categories['Hô hấp - Trị ho'],
                'supplier_id' => $suppliers['NCC001'],
                'unit' => 'Hộp',
                'price' => 65000,
                'stock' => 90,
                'expiry_date' => Carbon::now()->addMonths(18),
                'description' => 'Thành phần tinh dầu tràm, bạc hà, gừng và eucalyptol giúp làm ấm họng, long đờm và trị các chứng ho do gió, ho khan.',
            ],
            [
                'name' => 'Berocca Performance Hương Cam',
                'code' => 'BEROCCA',
                'category_id' => $categories['Vitamin & Khoáng chất'],
                'supplier_id' => $suppliers['NCC004'],
                'unit' => 'Tuýp',
                'price' => 88000,
                'stock' => 70,
                'expiry_date' => Carbon::now()->addMonths(14),
                'description' => 'Viên sủi bổ sung vitamin nhóm B, vitamin C, Kẽm và Canxi giúp tăng cường thể lực, chống mệt mỏi và tỉnh táo tinh thần.',
            ],
            [
                'name' => 'Salonpas Pain Relief Patch',
                'code' => 'SALONP',
                'category_id' => $categories['Giảm đau - Hạ sốt'],
                'supplier_id' => $suppliers['NCC002'],
                'unit' => 'Gói',
                'price' => 22000,
                'stock' => 180,
                'expiry_date' => Carbon::now()->addDays(15),
                'description' => 'Cao dán giảm đau dùng ngoài giúp giảm nhanh đau mỏi cơ bắp, đau khớp, đau lưng và bầm tím do chấn thương.',
            ],
            [
                'name' => 'Amlodipin 5mg',
                'code' => 'AMLO5',
                'category_id' => $categories['Tim mạch & Huyết áp'],
                'supplier_id' => $suppliers['NCC003'],
                'unit' => 'Hộp',
                'price' => 45000,
                'stock' => 0,
                'expiry_date' => Carbon::now()->addMonths(16),
                'description' => 'Thuốc chẹn kênh canxi điều trị tăng huyết áp vô căn và dự phòng các cơn đau thắt ngực ổn định.',
            ],
        ];

        $createdMedicines = [];
        foreach ($sampleMedicines as $med) {
            $createdMedicines[$med['code']] = Medicine::updateOrCreate(
                ['code' => $med['code']],
                $med
            );
        }

        // 5. Tạo mẫu 2 Hóa đơn bán thuốc (Invoices)
        $invoice1 = Invoice::updateOrCreate(
            ['code' => 'HD-20261029-001'],
            [
                'customer_name' => 'Anh Hoàng Văn Tuấn',
                'customer_phone' => '0933456789',
                'user_id' => $pharmacist->id,
                'total_amount' => 148000,
                'payment_method' => 'cash',
                'status' => 'paid',
                'note' => 'Uống sau ăn no, uống nhiều nước',
                'created_at' => Carbon::now()->subHours(4),
            ]
        );

        InvoiceDetail::updateOrCreate(
            ['invoice_id' => $invoice1->id, 'medicine_id' => $createdMedicines['PANEXT']->id],
            [
                'quantity' => 2,
                'unit_price' => 52000,
                'total_price' => 104000,
            ]
        );

        InvoiceDetail::updateOrCreate(
            ['invoice_id' => $invoice1->id, 'medicine_id' => $createdMedicines['SALONP']->id],
            [
                'quantity' => 2,
                'unit_price' => 22000,
                'total_price' => 44000,
            ]
        );

        $invoice2 = Invoice::updateOrCreate(
            ['code' => 'HD-20261029-002'],
            [
                'customer_name' => 'Chị Lê Thị Mai',
                'customer_phone' => '0944567890',
                'user_id' => $pharmacist->id,
                'total_amount' => 176000,
                'payment_method' => 'transfer',
                'status' => 'paid',
                'note' => 'Berocca uống sáng sau ăn',
                'created_at' => Carbon::now()->subHours(1),
            ]
        );

        InvoiceDetail::updateOrCreate(
            ['invoice_id' => $invoice2->id, 'medicine_id' => $createdMedicines['BEROCCA']->id],
            [
                'quantity' => 2,
                'unit_price' => 88000,
                'total_price' => 176000,
            ]
        );
    }
}
