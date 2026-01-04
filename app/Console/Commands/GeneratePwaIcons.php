<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GeneratePwaIcons extends Command
{
    protected $signature = 'pwa:generate-icons';

    protected $description = 'Generate PWA icons from site logo or create default icons';

    public function handle(): int
    {
        $iconsDir = public_path('icons');

        if (! is_dir($iconsDir)) {
            mkdir($iconsDir, 0755, true);
        }

        $siteLogo = Setting::get('site_logo');

        if ($siteLogo && Storage::disk('public')->exists($siteLogo)) {
            $logoPath = Storage::disk('public')->path($siteLogo);
            $this->resizeImage($logoPath, $iconsDir.'/icon-192x192.png', 192);
            $this->resizeImage($logoPath, $iconsDir.'/icon-512x512.png', 512);
            $this->info('PWA icons generated from site logo successfully!');
        } else {
            $this->createDefaultIcons($iconsDir);
            $this->info('Default PWA icons created successfully!');
        }

        return Command::SUCCESS;
    }

    protected function resizeImage(string $sourcePath, string $destinationPath, int $size): void
    {
        if (! extension_loaded('gd')) {
            $this->error('GD extension is not available. Please install it or manually create icon files.');

            return;
        }

        $imageInfo = getimagesize($sourcePath);
        if (! $imageInfo) {
            $this->warn("Could not read image: {$sourcePath}");

            return;
        }

        $sourceImage = match ($imageInfo[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
            IMAGETYPE_GIF => imagecreatefromgif($sourcePath),
            default => null,
        };

        if (! $sourceImage) {
            $this->warn("Unsupported image type: {$sourcePath}");

            return;
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        $newImage = imagecreatetruecolor($size, $size);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefill($newImage, 0, 0, $transparent);

        $sourceRatio = $sourceWidth / $sourceHeight;
        if ($sourceRatio > 1) {
            $newWidth = $size;
            $newHeight = (int) ($size / $sourceRatio);
            $x = 0;
            $y = (int) (($size - $newHeight) / 2);
        } else {
            $newWidth = (int) ($size * $sourceRatio);
            $newHeight = $size;
            $x = (int) (($size - $newWidth) / 2);
            $y = 0;
        }

        imagecopyresampled($newImage, $sourceImage, $x, $y, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

        imagepng($newImage, $destinationPath);
        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }

    protected function createDefaultIcons(string $iconsDir): void
    {
        if (! extension_loaded('gd')) {
            $this->error('GD extension is not available. Please install it to generate default icons.');

            return;
        }

        foreach ([192, 512] as $size) {
            $image = imagecreatetruecolor($size, $size);
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
            imagefill($image, 0, 0, $transparent);

            $red = imagecolorallocate($image, 220, 38, 38);
            $white = imagecolorallocate($image, 255, 255, 255);

            $centerX = $size / 2;
            $centerY = $size / 2;
            $radius = $size * 0.35;

            imagefilledellipse($image, $centerX, $centerY - $radius * 0.2, $radius * 2, $radius * 2.2, $red);

            $crossThickness = $size * 0.08;
            $crossLength = $size * 0.3;

            imagefilledrectangle(
                $image,
                (int) ($centerX - $crossThickness / 2),
                (int) ($centerY - $crossLength / 2),
                (int) ($centerX + $crossThickness / 2),
                (int) ($centerY + $crossLength / 2),
                $white
            );

            imagefilledrectangle(
                $image,
                (int) ($centerX - $crossLength / 2),
                (int) ($centerY - $crossThickness / 2),
                (int) ($centerX + $crossLength / 2),
                (int) ($centerY + $crossThickness / 2),
                $white
            );

            imagepng($image, $iconsDir."/icon-{$size}x{$size}.png");
            imagedestroy($image);
        }
    }
}
