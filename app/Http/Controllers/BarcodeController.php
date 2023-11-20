<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarcodeGenerateRequest;
use App\Services\BarcodeGeneratorService;

class BarcodeController extends Controller
{

    public function index()
    {
        return view('barcode');
    }

    public function generate(BarcodeGenerateRequest $request)
    {
        $barcodeService = new BarcodeGeneratorService( 'webp');
        $barcodeFilePath = $barcodeService->generate($request->input('barcode'));
        return view('barcode', ['barcode' => $barcodeFilePath]);
    }
}
