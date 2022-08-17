<?php

namespace App\Http\Controllers\Services;

use App\Imports\StockImport;
use App\Models\Stock;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class StockService
{
    public function __construct()
    {
        $this->stocks = Stock::get()->sortBy('id');
        $this->user = auth()->user();
    }

    public function updateStatus()
    {
        $this->stocks->map(function ($stock) {
            $usagePerMonth = $stock->annual_usage / 12;
            $twoMonthUsage = $usagePerMonth * 2;
            $fourMonthUsage = $usagePerMonth * 4;

            $item = $this->stocks->find($stock->id);

            if ($stock->balance < $twoMonthUsage) {
                $item->whereId($stock->id)->update(['status' => 'low']);
            } elseif (
                $stock->balance <= $fourMonthUsage
                && $stock->balance >= $twoMonthUsage
            ) {
                $item->whereId($stock->id)->update(['status' => 'medium']);
            } else {
                $item->whereId($stock->id)->update(['status' => 'high']);
            }
        });
    }

    public function fetchCreate($request)
    {
        $stock = new Stock();
        $stock->user_id = auth()->user()->id;
        $stock->code = $request->code;
        $stock->name = $request->name;
        $stock->common_name = $request->common_name;
        $stock->description = $request->description;
        $stock->balance = $request->balance;
        $stock->annual_usage = $request->usage;
        $stock->save();
    }

    public function showFilter($request)
    {
        $usagePeriod = $request->usage;
        $status = $request->status;
        $status == 'all' ? $stocks = $this->stocks : $stocks = $this->stocks->where('status', $status);

        $result = collect();

        $stocks->map(function ($stock) use ($result, $usagePeriod) {
            $usagePeriod == 'year' ? $usage = $stock->annual_usage : null;
            $usagePeriod == 'month' ? $usage = round($stock->annual_usage / 12, 2) : null;
            $usagePeriod == 'quarter' ? $usage = round($stock->annual_usage / 4, 2) : null;
            $usagePeriod == 'week' ? $usage = round($stock->annual_usage / 52, 2) : null;

            $result->push([
                'id' => $stock->id,
                'code' => $stock->code,
                'name' => $stock->name,
                'common_name' => $stock->common_name,
                'description' => $stock->description,
                'balance' => $stock->balance,
                'annual_usage' => $usage ? $usage : null,
                'status' => $stock->status,
            ]);
        });

        return $result;
    }

    public function fetchSave($request)
    {
        $ids = $request->id;
        $balance = $request->balance;
        $annualUsage = $request->annual_usage;
        $common = $request->common;
        $remark = $request->remark;

        foreach ($ids as $index => $id) {
            $stock = $this->stocks->find($id);
            $stock->whereId($id)->update([
                'balance' => $balance[$index],
                'annual_usage' => $annualUsage[$index],
                'common_name' => $common[$index],
                'remark' => $remark[$index],
            ]);
        }

        return Stock::get()->sortBy('id');
    }

    public function fetchImport($request)
    {
        $importedData = Excel::toArray(new StockImport, $request->file);

        if ($importedData) {
            $this->importService($importedData);
            Session::flash('message', 'Upload Successfully.');
            Session::flash('alert-class', 'alert-success');
            return Stock::get()->sortBy('id');
        } else {
            Session::flash('message', 'File not uploaded.');
            Session::flash('alert-class', 'alert-danger');
            return;
        }
    }

    public function importService($data)
    {
        $user_id = auth()->user()->id;
        foreach ($data as $rows) {
            foreach ($rows as $row) {
                if ($row['item_code']) {
                    Stock::updateOrCreate(
                        [
                            'user_id' => $user_id,
                            'code' => $row['item_code'] ? $row['item_code'] : 'null',
                        ],
                        [
                            'name' => $row['drug_non_drug_name'] ? $row['drug_non_drug_name'] : 'null',
                            'description' => $row['packaging_description'] ? $row['packaging_description'] : 'null',
                            'balance' => $row['total_stock_in_sku'] ? $row['total_stock_in_sku'] : 0
                        ]
                    );
                }
            }
        }
    }
}
