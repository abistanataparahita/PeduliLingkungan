<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_likes', function (Blueprint $table) {
            // Hapus unique constraint lama jika ada
            try {
                $table->dropUnique(['user_id', 'likeable_id', 'likeable_type']);
            } catch (\Exception $e) {
                // Abaikan jika constraint tidak ditemukan
            }

            // Tambah kolom type
            $table->enum('type', ['like', 'dislike'])->default('like')->after('likeable_type');

            // Tambah unique constraint baru yang menyertakan type
            // Sehingga 1 user bisa punya 1 like DAN 1 dislike pada entitas yang sama
            // tapi tidak bisa 2 like atau 2 dislike
            $table->unique(['user_id', 'likeable_id', 'likeable_type', 'type'], 'forum_likes_unique');
        });
    }

    public function down(): void
    {
        Schema::table('forum_likes', function (Blueprint $table) {
            $table->dropUnique('forum_likes_unique');
            $table->dropColumn('type');

            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }
};