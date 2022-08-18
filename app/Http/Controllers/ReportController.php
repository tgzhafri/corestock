<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Spatie\Browsershot\Browsershot;

class ReportController extends Controller
{
    public function index()
    {
        $stocks = auth()->user()->stock()->get()->sortBy('name');

        return view('report.index', ['stocks' => $stocks]);
    }

    public function generatePDF()
    {
        $stocks = auth()->user()->stock()->get()->sortBy('name');

        $content = view('report.generate', ['stocks' => $stocks])->render();

        $filePath = 'pdf/report.pdf';

        Browsershot::html($content)
            ->noSandbox()
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->margins(18, 18, 24, 18)
            ->format('A4')
            ->showBackground()
            ->save(storage_path($filePath));

        return response()->download(storage_path($filePath))->deleteFileAfterSend(true);
    }
}
