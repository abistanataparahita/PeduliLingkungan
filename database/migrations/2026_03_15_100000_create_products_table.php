<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->decimal('purchase_price', 14, 2)->default(0);
            $table->decimal('selling_price', 14, 2)->default(0);
            $table->decimal('discount_price', 14, 2)->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('current_stock')->default(0);
            $table->unsignedInteger('min_stock')->default(0);
            $table->unsignedInteger('max_stock')->nullable();
            $table->string('pcs', 50)->default('pcs');
            $table->boolean('is_preorder')->default(false);
            $table->string('preorder_estimate')->nullable();
            $table->date('preorder_open_until')->nullable();
            $table->unsignedInteger('preorder_quota')->nullable();
            $table->unsignedInteger('preorder_filled')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
