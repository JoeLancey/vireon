<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending')->after('order_number');
            }

            if (! Schema::hasColumn('orders', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('status');
            }

            if (! Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable()->after('recipient_name');
            }

            if (! Schema::hasColumn('orders', 'address_line1')) {
                $table->string('address_line1')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('orders', 'address_line2')) {
                $table->string('address_line2')->nullable()->after('address_line1');
            }

            if (! Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable()->after('address_line2');
            }

            if (! Schema::hasColumn('orders', 'province')) {
                $table->string('province')->nullable()->after('city');
            }

            if (! Schema::hasColumn('orders', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('province');
            }

            if (! Schema::hasColumn('orders', 'country')) {
                $table->string('country')->default('Philippines')->after('postal_code');
            }

            if (! Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('estimated_arrival');
            }

            if (! Schema::hasColumn('orders', 'shipped_at')) {
                $table->timestamp('shipped_at')->nullable()->after('tracking_number');
            }

            if (! Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            }

            if (! Schema::hasColumn('orders', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('orders', 'status') ? 'status' : null,
                Schema::hasColumn('orders', 'recipient_name') ? 'recipient_name' : null,
                Schema::hasColumn('orders', 'phone') ? 'phone' : null,
                Schema::hasColumn('orders', 'address_line1') ? 'address_line1' : null,
                Schema::hasColumn('orders', 'address_line2') ? 'address_line2' : null,
                Schema::hasColumn('orders', 'city') ? 'city' : null,
                Schema::hasColumn('orders', 'province') ? 'province' : null,
                Schema::hasColumn('orders', 'postal_code') ? 'postal_code' : null,
                Schema::hasColumn('orders', 'country') ? 'country' : null,
                Schema::hasColumn('orders', 'tracking_number') ? 'tracking_number' : null,
                Schema::hasColumn('orders', 'shipped_at') ? 'shipped_at' : null,
                Schema::hasColumn('orders', 'delivered_at') ? 'delivered_at' : null,
                Schema::hasColumn('orders', 'cancelled_at') ? 'cancelled_at' : null,
            ]);

            if ($columns) {
                $table->dropColumn($columns);
            }
        });
    }
};