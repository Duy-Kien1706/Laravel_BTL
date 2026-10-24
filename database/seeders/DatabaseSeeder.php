<?php

namespace Database\Seeders;

use App\Models\Medicine;
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
        // 1. Tạo tài khoản Admin mặc định
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản Trị Viên (Admin)',
                'password' => Hash::make('12345678'),
            ]
        );

        // 2. Tạo danh sách các loại thuốc thực tế mẫu
        $sampleMedicines = [
            [
                'name' => 'Paracetamol 500mg',
                'code' => 'PARA500',
                'category' => 'Giảm đau - Hạ sốt',
                'unit' => 'Hộp',
                'price' => 35000,
                'stock' => 120,
                'expiry_date' => Carbon::now()->addMonths(18),
                'description' => 'Thuốc giảm đau nhanh, hạ sốt cho người lớn và trẻ em trên 12 tuổi trong các trường hợp cảm cúm, đau đầu, đau răng.',
            ],
            [
                'name' => 'Panadol Extra Đỏ',
                'code' => 'PANEXT',
                'category' => 'Giảm đau - Hạ sốt',
                'unit' => 'Hộp',
                'price' => 52000,
                'stock' => 85,
                'expiry_date' => Carbon::now()->addMonths(24),
                'description' => 'Chứa Paracetamol và Caffeine giúp giảm đau nhanh chóng các cơn đau dai dẳng, hạ sốt hiệu quả mà không gây buồn ngủ.',
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'code' => 'AMOX500',
                'category' => 'Kháng sinh',
                'unit' => 'Vỉ',
                'price' => 28000,
                'stock' => 8, // Cảnh báo sắp hết hàng
                'expiry_date' => Carbon::now()->addMonths(12),
                'description' => 'Kháng sinh nhóm Penicillin, chỉ định điều trị nhiễm khuẩn đường hô hấp trên và dưới, nhiễm khuẩn đường tiết niệu.',
            ],
            [
                'name' => 'Augmentin 1g',
                'code' => 'AUG1G',
                'category' => 'Kháng sinh',
                'unit' => 'Hộp',
                'price' => 185000,
                'stock' => 45,
                'expiry_date' => Carbon::now()->addDays(20), // Cảnh báo sắp hết hạn
                'description' => 'Kháng sinh phổ rộng kết hợp Amoxicillin và Acid Clavulanic điều trị viêm tai giữa, viêm xoang, viêm phổi, nhiễm trùng da.',
            ],
            [
                'name' => 'Berberin 100mg',
                'code' => 'BERB100',
                'category' => 'Tiêu hóa - Dạ dày',
                'unit' => 'Lọ',
                'price' => 18000,
                'stock' => 200,
                'expiry_date' => Carbon::now()->addMonths(30),
                'description' => 'Chiết xuất từ thảo dược thiên nhiên, điều trị viêm ruột, tiêu chảy, kiết lỵ do vi khuẩn an toàn và hiệu quả.',
            ],
            [
                'name' => 'Smecta 3g',
                'code' => 'SMECTA3',
                'category' => 'Tiêu hóa - Dạ dày',
                'unit' => 'Hộp',
                'price' => 115000,
                'stock' => 60,
                'expiry_date' => Carbon::now()->addMonths(15),
                'description' => 'Thuốc bột pha hỗn dịch uống điều trị triệu chứng tiêu chảy cấp ở người lớn và trẻ em kết hợp bù nước.',
            ],
            [
                'name' => 'Gaviscon Dual Action',
                'code' => 'GAVISD',
                'category' => 'Tiêu hóa - Dạ dày',
                'unit' => 'Hộp',
                'price' => 145000,
                'stock' => 5, // Cảnh báo sắp hết hàng
                'expiry_date' => Carbon::now()->addMonths(8),
                'description' => 'Dạng gói hỗn dịch giảm nhanh các triệu chứng trào ngược dạ dày thực quản, ợ chua, khó tiêu do thừa acid.',
            ],
            [
                'name' => 'Decolgen Forte',
                'code' => 'DECOLF',
                'category' => 'Hô hấp - Trị ho',
                'unit' => 'Vỉ',
                'price' => 15000,
                'stock' => 150,
                'expiry_date' => Carbon::now()->addMonths(20),
                'description' => 'Điều trị các triệu chứng cảm cúm thông thường: sốt, nhức đầu, sổ mũi, nghẹt mũi và hắt hơi nhiều.',
            ],
            [
                'name' => 'Eugica Đỏ viên nang mềm',
                'code' => 'EUGICARD',
                'category' => 'Hô hấp - Trị ho',
                'unit' => 'Hộp',
                'price' => 65000,
                'stock' => 90,
                'expiry_date' => Carbon::now()->addMonths(18),
                'description' => 'Thành phần tinh dầu tràm, bạc hà, gừng và eucalyptol giúp làm ấm họng, long đờm và trị các chứng ho do gió, ho khan.',
            ],
            [
                'name' => 'Berocca Performance Hương Cam',
                'code' => 'BEROCCA',
                'category' => 'Vitamin & Khoáng chất',
                'unit' => 'Tuýp',
                'price' => 88000,
                'stock' => 70,
                'expiry_date' => Carbon::now()->addMonths(14),
                'description' => 'Viên sủi bổ sung vitamin nhóm B, vitamin C, Kẽm và Canxi giúp tăng cường thể lực, chống mệt mỏi và tỉnh táo tinh thần.',
            ],
            [
                'name' => 'Salonpas Pain Relief Patch',
                'code' => 'SALONP',
                'category' => 'Giảm đau - Hạ sốt',
                'unit' => 'Gói',
                'price' => 22000,
                'stock' => 180,
                'expiry_date' => Carbon::now()->addDays(15), // Cảnh báo sắp hết hạn
                'description' => 'Cao dán giảm đau dùng ngoài giúp giảm nhanh đau mỏi cơ bắp, đau khớp, đau lưng và bầm tím do chấn thương.',
            ],
            [
                'name' => 'Amlodipin 5mg',
                'code' => 'AMLO5',
                'category' => 'Tim mạch & Huyết áp',
                'unit' => 'Hộp',
                'price' => 45000,
                'stock' => 0, // Hết hàng
                'expiry_date' => Carbon::now()->addMonths(16),
                'description' => 'Thuốc chẹn kênh canxi điều trị tăng huyết áp vô căn và dự phòng các cơn đau thắt ngực ổn định.',
            ],
        ];

        foreach ($sampleMedicines as $med) {
            Medicine::updateOrCreate(
                ['code' => $med['code']],
                $med
            );
        }
    }
}
