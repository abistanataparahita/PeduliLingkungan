<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('orders', 'product_id')) {
                $table->foreignId('product_id')->after('user_id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('orders', 'buyer_name')) {
                $table->string('buyer_name')->after('product_id');
            }
            if (! Schema::hasColumn('orders', 'whatsapp')) {
                $table->string('whatsapp', 30)->after('buyer_name');
            }
            if (! Schema::hasColumn('orders', 'qty')) {
                $table->unsignedInteger('qty')->after('whatsapp');
            }
            if (! Schema::hasColumn('orders', 'catatan')) {
                $table->text('catatan')->nullable()->after('qty');
            }
            if (! Schema::hasColumn('orders', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'selesai', 'dibatalkan'])->default('pending')->index()->after('catatan');
            }
            if (! Schema::hasColumn('orders', 'is_read')) {
                $table->boolean('is_read')->default(false)->index()->after('status');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            foreach (['is_read', 'status', 'catatan', 'qty', 'whatsapp', 'buyer_name', 'product_id', 'user_id'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

