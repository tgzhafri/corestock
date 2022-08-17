<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Spatie\Browsershot\Browsershot;

class ReportController extends Controller
{
    public function index()
    {
        $stocks = auth()->user()->stock()->get()->sortBy('id');

        return view('report.index', ['stocks' => $stocks]);
    }

    public function generatePDF()
    {
        // retreive all records from db
        // $stocks = Stock::with('supplier')->get()->toArray();
        $stocks = auth()->user()->stock()->get()->sortBy('id');

        $content = view('report.generate', ['stocks' => $stocks])->render();

        return Browsershot::html($content)
            ->noSandbox()
            ->showBackground()
            ->margins(18, 18, 24, 18)
            ->format('A4')
            ->showBackground()
            ->save(storage_path('pdf/report.pdf'));
    }
}
