<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pedulilingkungan.id'],
            [
                'name' => 'Admin Peduli Lingkungan',
                'email' => 'admin@pedulilingkungan.id',
                'password' => Hash::make('purbalinggabersih@2026'),
                'role' => 'admin',
            ]
        );
    }
}
