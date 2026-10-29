<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['import', 'export'])->default('import'); // import: Nhập kho, export: Xuất kho
            $table->string('supplier')->nullable(); // Nhà cung cấp hoặc lý do xuất
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('warehouse_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_receipt_id')->constrained('warehouse_receipts')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 14, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_receipt_details');
        Schema::dropIfExists('warehouse_receipts');
    }
};
