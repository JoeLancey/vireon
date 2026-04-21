<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "XS", "S", "M", "L", "XL", etc.
            $table->string('value'); // e.g., "xs", "s", "m", "l", "xl"
            $table->string('category'); // e.g., "clothing", "footwear"
            $table->unique(['value', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
