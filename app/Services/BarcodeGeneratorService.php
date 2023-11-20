<?php

namespace App\Services;

use App\Interfaces\BarcodeGeneratorInterface;
use Intervention\Image\Facades\Image;
use InvalidArgumentException;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Str;

class BarcodeGeneratorService implements BarcodeGeneratorInterface
{
    private DNS1D $barcodeGenerator;
    private string $barcodeImageFormat;

    public function __construct(?string $barcodeOutputImageFormat)
    {
        $this->barcodeGenerator = new DNS1D;
        $this->barcodeImageFormat = $this->validateImageFormat($barcodeOutputImageFormat) ? $barcodeOutputImageFormat : "webp";
        $this->createDirectory();
    }

    public function generate(string $barcodeData): string
    {
        $barcodeImage = $this->barcodeGenerator->getBarcodePNG($barcodeData, 'C39');
        $outputFilePath = $this->getOutputFilePath($barcodeData);
        $this->saveImage($barcodeImage, $outputFilePath);
        return $outputFilePath;
    }

    private function getOutputFilePath($text): string
    {
        $directory = public_path(config('app.barcodes_path', 'barcodes'));
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        return $directory . '/barcode-' . Str::random(10) . '-' . $text . '.' . $this->barcodeImageFormat;
    }

    private function saveImage($barcode, $outputFile): void
    {
        $image = Image::make($barcode)->encode( $this->barcodeImageFormat, 90);
        $image->save($outputFile);
    }

    private function createDirectory(): void
    {
        $directory = public_path(config('app.barcodes_path', 'barcodes'));
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    private function validateImageFormat(?string $format): bool
    {
        $supportedFormats = ['webp', 'png', 'jpg', 'jpeg', 'gif'];
        if ($format && !in_array(strtolower($format), $supportedFormats)) {
            throw new InvalidArgumentException("Unsupported image format: {$format}");
        }
        return true;
    }
}
