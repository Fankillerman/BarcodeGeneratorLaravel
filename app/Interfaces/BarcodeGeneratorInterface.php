<?php

namespace App\Interfaces;

interface BarcodeGeneratorInterface
{
    public function generate(string $barcodeData): string;

}
