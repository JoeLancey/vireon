<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('orders', 'coupon_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
                $table->decimal('coupon_discount', 10, 2)->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'coupon_id')) {
                $table->dropForeignIdFor(\App\Models\Coupon::class);
                $table->dropColumn('coupon_id');
            }
            if (Schema::hasColumn('orders', 'coupon_discount')) {
                $table->dropColumn('coupon_discount');
            }
        });
    }
};
