<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    public function store(UploadedFile $file, string $directory, int $maxWidth = 1600, int $quality = 82, bool $withWatermark = false): string
    {
        return $this->processAndStore($file, $directory, $maxWidth, $quality, $withWatermark);
    }

    public function storeWithThumbnail(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1600,
        int $thumbWidth = 600,
        int $quality = 82,
        bool $withWatermark = false
    ): array
    {
        if (!extension_loaded('gd')) {
            return [
                'image' => $file->store($directory, 'public'),
                'thumb' => null,
            ];
        }

        return [
            'image' => $this->processAndStore($file, $directory, $maxWidth, $quality, $withWatermark),
            'thumb' => $this->processAndStore($file, $directory.'/thumbs', $thumbWidth, $quality, $withWatermark),
        ];
    }

    public function applyWatermarkToPublicPath(string $path, int $quality = 82): bool
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        $path = ltrim($path, '/');
        if ($path === '' || !Storage::disk('public')->exists($path)) {
            return false;
        }

        $absolutePath = Storage::disk('public')->path($path);
        $extension = strtolower((string) pathinfo($absolutePath, PATHINFO_EXTENSION));
        $extension = $this->normalizeExtension($extension);

        $image = $this->createImageFromFile($absolutePath, $extension);
        if (! $image) {
            return false;
        }

        $this->applyLogoWatermark($image);

        $filename = basename($path);
        $tmpPath = sys_get_temp_dir().'/wm-'.$filename;
        $this->saveImage($image, $tmpPath, $extension, $quality);

        imagedestroy($image);

        Storage::disk('public')->put($path, fopen($tmpPath, 'rb'));
        @unlink($tmpPath);

        return true;
    }

    private function processAndStore(UploadedFile $file, string $directory, int $maxWidth, int $quality, bool $withWatermark = false): string
    {
        if (!extension_loaded('gd')) {
            return $file->store($directory, 'public');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $extension = $this->normalizeExtension($extension);

        $image = $this->createImageFromFile($file->getPathname(), $extension);
        if (! $image) {
            return $file->store($directory, 'public');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $targetWidth = $width;
        $targetHeight = $height;

        if ($width > $maxWidth) {
            $ratio = $height / $width;
            $targetWidth = $maxWidth;
            $targetHeight = (int) round($maxWidth * $ratio);
        }

        $processed = imagecreatetruecolor($targetWidth, $targetHeight);
        $this->preserveTransparency($processed, $extension);

        imagecopyresampled(
            $processed,
            $image,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $width,
            $height
        );

        if ($withWatermark) {
            $this->applyLogoWatermark($processed);
        }

        $filename = Str::uuid()->toString().'.'.$extension;
        $path = trim($directory, '/').'/'.$filename;
        $tmpPath = sys_get_temp_dir().'/'.$filename;

        $this->saveImage($processed, $tmpPath, $extension, $quality);

        imagedestroy($image);
        imagedestroy($processed);

        Storage::disk('public')->put($path, fopen($tmpPath, 'rb'));
        @unlink($tmpPath);

        return $path;
    }

    private function normalizeExtension(string $extension): string
    {
        if (in_array($extension, ['jpeg', 'jpg'], true)) {
            return 'jpg';
        }

        if ($extension === 'png') {
            return 'png';
        }

        if ($extension === 'webp' && function_exists('imagewebp')) {
            return 'webp';
        }

        return 'jpg';
    }

    private function createImageFromFile(string $path, string $extension)
    {
        return match ($extension) {
            'jpg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : null,
            default => null,
        };
    }

    private function saveImage($image, string $path, string $extension, int $quality): void
    {
        if ($extension === 'png') {
            $compression = (int) round((100 - min(max($quality, 0), 100)) / 10);
            imagepng($image, $path, $compression);
            return;
        }

        if ($extension === 'webp' && function_exists('imagewebp')) {
            imagewebp($image, $path, $quality);
            return;
        }

        imagejpeg($image, $path, $quality);
    }

    private function preserveTransparency($image, string $extension): void
    {
        if ($extension !== 'png') {
            return;
        }

        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefilledrectangle($image, 0, 0, imagesx($image), imagesy($image), $transparent);
    }

    private function applyLogoWatermark($canvas): void
    {
        $logoPath = public_path('img/logo2.png');
        if (!is_file($logoPath) || !is_readable($logoPath)) {
            $logoPath = public_path('img/logo.png');
        }
        if (!is_file($logoPath) || !is_readable($logoPath)) {
            return;
        }

        $logo = @imagecreatefrompng($logoPath);
        if (!$logo) {
            return;
        }

        $canvasWidth = imagesx($canvas);
        $canvasHeight = imagesy($canvas);
        $logoWidth = imagesx($logo);
        $logoHeight = imagesy($logo);

        if ($canvasWidth < 220 || $canvasHeight < 180 || $logoWidth <= 0 || $logoHeight <= 0) {
            imagedestroy($logo);
            return;
        }

        $targetLogoWidth = (int) round($canvasWidth * 0.24);
        $targetLogoWidth = max(90, min(280, $targetLogoWidth));
        $targetLogoWidth = min($targetLogoWidth, (int) floor($canvasWidth * 0.45));
        $ratio = $logoHeight / $logoWidth;
        $targetLogoHeight = (int) round($targetLogoWidth * $ratio);

        $resizedLogo = imagecreatetruecolor($targetLogoWidth, $targetLogoHeight);
        imagealphablending($resizedLogo, false);
        imagesavealpha($resizedLogo, true);
        $transparent = imagecolorallocatealpha($resizedLogo, 0, 0, 0, 127);
        imagefilledrectangle($resizedLogo, 0, 0, $targetLogoWidth, $targetLogoHeight, $transparent);

        imagecopyresampled(
            $resizedLogo,
            $logo,
            0,
            0,
            0,
            0,
            $targetLogoWidth,
            $targetLogoHeight,
            $logoWidth,
            $logoHeight
        );

        // Watermark difuminado: mantenemos transparencia original y bajamos opacidad global.
        $this->applyImageOpacity($resizedLogo, 30);

        imagealphablending($canvas, true);
        imagesavealpha($canvas, true);

        $x = (int) round(($canvasWidth - $targetLogoWidth) / 2);
        $y = (int) round(($canvasHeight - $targetLogoHeight) / 2);

        imagecopy($canvas, $resizedLogo, $x, $y, 0, 0, $targetLogoWidth, $targetLogoHeight);

        imagedestroy($resizedLogo);
        imagedestroy($logo);
    }

    private function applyImageOpacity($image, int $opacity): void
    {
        $opacity = max(0, min(100, $opacity));
        $width = imagesx($image);
        $height = imagesy($image);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $rgba = imagecolorat($image, $x, $y);
                $alpha = ($rgba >> 24) & 0x7F;
                $red = ($rgba >> 16) & 0xFF;
                $green = ($rgba >> 8) & 0xFF;
                $blue = $rgba & 0xFF;

                if ($alpha >= 127) {
                    continue;
                }

                $visible = 127 - $alpha;
                $scaledVisible = (int) round($visible * ($opacity / 100));
                $newAlpha = 127 - $scaledVisible;
                $newAlpha = max(0, min(127, $newAlpha));

                $newColor = imagecolorallocatealpha($image, $red, $green, $blue, $newAlpha);
                imagesetpixel($image, $x, $y, $newColor);
            }
        }
    }
}
