<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'coupon_id')) {
                    try {
                        $table->dropForeign(['coupon_id']);
                    } catch (\Throwable $e) {
                        // Ignore if foreign key does not exist.
                    }

                    $table->dropColumn('coupon_id');
                }

                if (Schema::hasColumn('orders', 'coupon_discount')) {
                    $table->dropColumn('coupon_discount');
                }
            });
        }

        if (Schema::hasTable('coupons')) {
            Schema::drop('coupons');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->enum('discount_type', ['percentage', 'fixed']);
                $table->decimal('discount_value', 10, 2);
                $table->decimal('min_order_amount', 10, 2)->default(0);
                $table->integer('max_uses')->nullable();
                $table->integer('current_uses')->default(0);
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'coupon_id')) {
                    $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
                }

                if (! Schema::hasColumn('orders', 'coupon_discount')) {
                    $table->decimal('coupon_discount', 10, 2)->default(0);
                }
            });
        }
    }
};
