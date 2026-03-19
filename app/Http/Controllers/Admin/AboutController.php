<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function edit()
    {
        $data = [
            'about_vision' => setting('about_vision'),
            'about_mission_1' => setting('about_mission_1'),
            'about_mission_2' => setting('about_mission_2'),
            'about_mission_3' => setting('about_mission_3'),
            'about_mission_4' => setting('about_mission_4'),
            'about_mission_5' => setting('about_mission_5'),
            'about_image' => setting('about_image'),
        ];

        return view('admin.about.edit', $data);
    }

    public function update(Request $request, ImageUploadService $uploader)
    {
        $validated = $request->validate([
            'about_vision' => ['nullable', 'string'],
            'about_mission_1' => ['nullable', 'string'],
            'about_mission_2' => ['nullable', 'string'],
            'about_mission_3' => ['nullable', 'string'],
            'about_mission_4' => ['nullable', 'string'],
            'about_mission_5' => ['nullable', 'string'],
            'about_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $keys = [
            'about_vision',
            'about_mission_1',
            'about_mission_2',
            'about_mission_3',
            'about_mission_4',
            'about_mission_5',
        ];

        foreach ($keys as $key) {
            if (array_key_exists($key, $validated)) {
                SiteSetting::set($key, $validated[$key]);
            }
        }

        if ($request->hasFile('about_image')) {
            $path = $uploader->upload(
                $request->file('about_image'),
                'about',
                ImageUploadService::BANNERS
            );

            SiteSetting::set('about_image', $path);
        }

        return redirect()
            ->route('admin.about.edit')
            ->with('success', 'Informasi Tentang Kami berhasil disimpan.');
    }
}

