<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class StorageHelper
{
    /**
     * Get the active storage disk for uploaded files.
     */
    public static function disk(): string
    {
        if (config('filesystems.disks.google.refresh_token') && config('filesystems.disks.google.folder_id')) {
            return 'google';
        }

        return 'public';
    }

    /**
     * Get accessible URL for browser viewing (supports both Google Drive and local storage).
     */
    public static function url(?string $path): string
    {
        if (!$path) {
            return '';
        }

        $cleanPath = ltrim($path, '/\\');

        // If file exists locally in public storage, serve via direct static asset URL
        if (file_exists(storage_path('app/public/' . $cleanPath))) {
            return asset('storage/' . $cleanPath);
        }

        // For Google Drive or cloud-stored files, serve via streaming controller
        return route('storage.file', ['path' => $cleanPath]);
    }

    /**
     * Get the binary content of a file from google or public disk.
     */
    public static function get(string $path): ?string
    {
        $cleanPath = ltrim($path, '/\\');

        try {
            if (self::disk() === 'google' && Storage::disk('google')->exists($cleanPath)) {
                return Storage::disk('google')->get($cleanPath);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('StorageHelper::get google disk error: ' . $e->getMessage());
        }

        try {
            if (Storage::disk('public')->exists($cleanPath)) {
                return Storage::disk('public')->get($cleanPath);
            }
        } catch (\Throwable $e) {}

        $localPath = storage_path('app/public/' . $cleanPath);
        if (file_exists($localPath)) {
            return file_get_contents($localPath);
        }

        return null;
    }

    /**
     * Get base64 encoded data URI for embedding into PDF.
     */
    public static function getBase64(string $path): ?string
    {
        $content = self::get($path);
        if (!$content) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($extension) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'pdf' => 'application/pdf',
            default => 'image/jpeg',
        };

        return 'data:' . $mime . ';base64,' . base64_encode($content);
    }

    /**
     * Delete a file from storage.
     */
    public static function delete(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        $cleanPath = ltrim($path, '/\\');

        try {
            if (self::disk() === 'google' && Storage::disk('google')->exists($cleanPath)) {
                return Storage::disk('google')->delete($cleanPath);
            }

            if (Storage::disk('public')->exists($cleanPath)) {
                return Storage::disk('public')->delete($cleanPath);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('StorageHelper::delete failed: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Compress and store an uploaded image to cloud storage or local disk.
     * Dramatically reduces upload time, memory, and bandwidth.
     */
    public static function storeCompressedImage($file, string $folder = 'laporan-kegiatan', int $maxDimension = 1280, int $quality = 80): string
    {
        if (!$file || !$file->isValid()) {
            throw new \Exception('Berkas yang diunggah tidak valid.');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        // If not a standard image or GD is missing, store directly
        if (!in_array($extension, $imageExtensions) || !function_exists('imagecreatefromstring')) {
            return $file->store($folder, self::disk());
        }

        $filePath = $file->getRealPath();
        $fileContents = file_get_contents($filePath);
        if (!$fileContents) {
            return $file->store($folder, self::disk());
        }

        $srcImg = @imagecreatefromstring($fileContents);
        if (!$srcImg) {
            return $file->store($folder, self::disk());
        }

        // Fix EXIF orientation for smartphone photos
        if (function_exists('exif_read_data') && in_array($extension, ['jpg', 'jpeg'])) {
            try {
                $exif = @exif_read_data($filePath);
                if (!empty($exif['Orientation'])) {
                    $srcImg = match ($exif['Orientation']) {
                        3 => imagerotate($srcImg, 180, 0),
                        6 => imagerotate($srcImg, -90, 0),
                        8 => imagerotate($srcImg, 90, 0),
                        default => $srcImg
                    };
                }
            } catch (\Throwable $e) {}
        }

        $origW = imagesx($srcImg);
        $origH = imagesy($srcImg);

        // Calculate resize dimensions
        if ($origW > $maxDimension || $origH > $maxDimension) {
            if ($origW >= $origH) {
                $newW = $maxDimension;
                $newH = (int) round(($origH / $origW) * $maxDimension);
            } else {
                $newH = $maxDimension;
                $newW = (int) round(($origW / $origH) * $maxDimension);
            }
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        $dstImg = imagecreatetruecolor($newW, $newH);

        // Preserve PNG transparency or fill white for JPEG
        if ($extension === 'png') {
            imagealphablending($dstImg, false);
            imagesavealpha($dstImg, true);
            $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
            imagefilledrectangle($dstImg, 0, 0, $newW, $newH, $transparent);
        } else {
            $white = imagecolorallocate($dstImg, 255, 255, 255);
            imagefilledrectangle($dstImg, 0, 0, $newW, $newH, $white);
        }

        imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Output compressed binary
        ob_start();
        if ($extension === 'png') {
            imagepng($dstImg, null, 7);
            $outExt = 'png';
        } else {
            imagejpeg($dstImg, null, $quality);
            $outExt = 'jpg';
        }
        $compressedData = ob_get_clean();

        imagedestroy($srcImg);
        imagedestroy($dstImg);

        $filename = \Illuminate\Support\Str::random(40) . '.' . $outExt;
        $targetPath = trim($folder, '/') . '/' . $filename;

        // Save to target storage (Google Drive / Local)
        Storage::disk(self::disk())->put($targetPath, $compressedData);

        // Also save to local temp cache for fast PDF generation immediately
        try {
            $tempDir = storage_path('app/temp-pdf-images');
            if (!is_dir($tempDir)) {
                @mkdir($tempDir, 0755, true);
            }
            file_put_contents($tempDir . '/' . md5($targetPath) . '.' . $outExt, $compressedData);
        } catch (\Throwable $e) {}

        return $targetPath;
    }
}
