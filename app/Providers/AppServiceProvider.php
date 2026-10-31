<?php

namespace App\Providers;

use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            \App\Models\User::where('role', 'warehouse')->delete();

            if (!app()->runningInConsole() && !Schema::hasTable('suppliers')) {
                Schema::create('suppliers', function (Blueprint $table) {
                    $table->id();
                    $table->string('code')->unique();
                    $table->string('name');
                    $table->string('phone')->nullable();
                    $table->string('email')->nullable();
                    $table->string('address')->nullable();
                    $table->text('description')->nullable();
                    $table->timestamps();
                });

                if (Schema::hasTable('medicines') && !Schema::hasColumn('medicines', 'supplier_id')) {
                    Schema::table('medicines', function (Blueprint $table) {
                        $table->foreignId('supplier_id')->nullable()->after('category_id')->constrained('suppliers')->nullOnDelete();
                    });
                }

                Schema::dropIfExists('warehouse_receipt_details');
                Schema::dropIfExists('warehouse_receipts');

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

                $supMap = [];
                foreach ($suppliersData as $sup) {
                    $created = Supplier::updateOrCreate(['code' => $sup['code']], $sup);
                    $supMap[$sup['code']] = $created->id;
                }

                // Gán nhà cung cấp mẫu cho các thuốc sẵn có
                Medicine::whereNull('supplier_id')->chunkById(50, function ($meds) use ($supMap) {
                    $codes = array_values($supMap);
                    foreach ($meds as $idx => $med) {
                        $med->update(['supplier_id' => $codes[$idx % count($codes)]]);
                    }
                });
            }
        } catch (\Throwable $e) {
            // Bỏ qua nếu CSDL chưa kết nối
        }
    }
}
