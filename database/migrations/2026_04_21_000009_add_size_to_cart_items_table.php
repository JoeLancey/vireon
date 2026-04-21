<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('cart_items', 'size_id')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->foreignId('size_id')->nullable()->constrained('sizes')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            if (Schema::hasColumn('cart_items', 'size_id')) {
                $table->dropForeignIdFor(\App\Models\Size::class);
                $table->dropColumn('size_id');
            }
        });
    }
};
