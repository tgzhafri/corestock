<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Services\StockService;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function __construct(StockService $service)
    {
        $this->stockService = $service;
    }

    public function index()
    {
        $stocks = auth()->user()->stock()->get()->sortBy('id');
        $this->stockService->updateStatus();

        return view('stock.index', ['stocks' => $stocks]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:stocks',
            'name' => 'required',
            'balance' => 'required',
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $this->stockService->fetchCreate($request);
        return Redirect::back();
    }

    public function edit()
    {
        $stocks = auth()->user()->stock()->get()->sortBy('id');

        return view('stock.edit', ['stocks' => $stocks]);
    }

    public function show(Request $request)
    {
        $result = $this->stockService->showFilter($request);
        return view('stock.index', ['stocks' => $result]);
    }

    public function delete(Request $request)
    {
        $stock = Stock::find($request->stock_id);
        $stock->forceDelete();

        return Redirect::back();
    }

    public function save(Request $request)
    {
        $result = $this->stockService->fetchSave($request);

        return Redirect::route('stock.index', ['stocks' => $result]);
    }

    public function import(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx|max:2048'
        ]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $result = $this->stockService->fetchImport($request);

        if ($result) {
            return Redirect::route('stock.index', ['stocks' => $result]);
        } else {
            return Redirect::back();
        }
    }
}
