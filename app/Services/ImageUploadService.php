<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    public const BANNERS = 1920;
    public const EVENTS = 1200;
    public const GALLERIES = 1400;
    public const ARTICLES = 800;
    public const AVATARS = 400;
    public const PRODUCTS = 800;

    protected ImageManager $manager;
    protected string $disk;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
        $this->disk = config('filesystems.uploads_disk', 'public');
    }

    public function upload(UploadedFile $file, string $directory, int $maxWidth, int $quality = 80): string
    {
        // Jika menggunakan disk lokal "public", lakukan resize + konversi WebP seperti sebelumnya.
        if ($this->disk === 'public') {
            $image = $this->manager->read($file->getPathname());

            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            $filename = uniqid() . '.webp';
            $path = rtrim($directory, '/') . '/' . $filename;

            $encoded = $image->toWebp(quality: $quality);

            Storage::disk($this->disk)->put($path, (string) $encoded);

            return $path;
        }

        // Jika menggunakan disk lain (misal S3 / Cloudinary), simpan file apa adanya
        // dan biarkan layanan eksternal yang mengatur optimasi.
        return $file->store(
            rtrim($directory, '/'),
            $this->disk
        );
    }

    public function delete(string $path): bool
    {
        return Storage::disk($this->disk)->delete($path);
    }
}
