<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        if (! SiteSetting::where('key', 'about_image')->exists()) {
            SiteSetting::create([
                'key' => 'about_image',
                'value' => null,
                'type' => 'image',
                'label' => 'Gambar Tentang Kami',
                'group' => 'about',
            ]);
        }

        foreach (range(1, 5) as $i) {
            $key = "about_mission_{$i}";
            if (! SiteSetting::where('key', $key)->exists()) {
                SiteSetting::create([
                    'key' => $key,
                    'value' => null,
                    'type' => 'text',
                    'label' => "Misi {$i}",
                    'group' => 'about',
                ]);
            }
        }
    }

    public function down(): void
    {
        SiteSetting::whereIn('key', [
            'about_image',
            'about_mission_1',
            'about_mission_2',
            'about_mission_3',
            'about_mission_4',
            'about_mission_5',
        ])->delete();
    }
};

