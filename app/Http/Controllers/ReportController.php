<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Http\Controllers\Services\StockService;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Browsershot\Browsershot;

class ReportController extends Controller
{
    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        !isset($_GET['status']) ? $_GET['status'] = 'all' : null;
        !isset($_GET['usage']) ? $_GET['usage'] = 'year' : null;

        $stocks = auth()->user()->stock()->get()->sortBy('name');

        return view('report.index', View::share('stocks', $stocks));
    }

    public function generatePDF()
    {
        $stocks = $this->stockService->showFilter();
        $content = view('report.generate', View::share('stocks', $stocks))->render();

        $carbon = Carbon::now()->timestamp;
        $filePath = "pdf/{$carbon}-corestock-report.pdf";

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

    public function downloadExcel()
    {
        $unix = Carbon::now()->timestamp;
        $stocks = $this->stockService->showFilter();

        return Excel::download(new ReportExport($stocks), "Corestock-report-$unix.xlsx");
    }

    public function downloadPdf()
    {
        $unix = Carbon::now()->timestamp;
        $stocks = $this->stockService->showFilter();

        return (new ReportExport($stocks))->download("Corestock-report-$unix.pdf", \Maatwebsite\Excel\Excel::DOMPDF);
    }


    public function show()
    {
        !isset($_GET['status']) ? $_GET['status'] = 'all' : null;
        !isset($_GET['usage']) ? $_GET['usage'] = 'year' : null;

        $stocks = $this->stockService->showFilter();
        return view('report.index', View::share('stocks', $stocks));
    }
}
