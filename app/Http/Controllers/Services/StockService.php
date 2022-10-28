<?php

namespace App\Http\Controllers\Services;

use App\Imports\StockImport;
use App\Models\Stock;
use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class StockService
{
    public function __construct()
    {
        $this->user = auth()->user();
    }

    public function updateStatus()
    {
        $this->stocks = auth()->user()->stock()->get()->sortBy('name');

        $this->stocks->map(function ($stock) {
            $usagePerMonth = $stock->annual_usage / 12;
            $twoMonthUsage = $usagePerMonth * 2;
            $fourMonthUsage = $usagePerMonth * 4;

            $item = $this->stocks->find($stock->id);

            if ($stock->balance < $twoMonthUsage) {
                $item->whereId($stock->id)->update(['status' => 'low']);
            } elseif (
                $stock->balance == 0 && $stock->annual_usage == 0
            ) {
                $item->whereId($stock->id)->update(['status' => 'n/a']);
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

    public function showFilter()
    {
        $this->stocks = auth()->user()->stock()->with('supplier')->get()->sortBy('name');

        $usagePeriod = request('usage');
        $status = request('status');
        !$status || $status == 'all' ? $stocks = $this->stocks : $stocks = $this->stocks->where('status', $status);

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
                'annual_usage' => $usage ?? null,
                'status' => $stock->status,
                'supplier' => $stock->supplier,
                'remark' => $stock->remark
            ]);
        });

        return $result;
    }

    public function fetchSave($request)
    {
        $stocks = auth()->user()->stock()->get()->sortBy('name');
        $ids = $request->id;
        $balance = $request->balance;
        $annualUsage = $request->annual_usage;
        $common = $request->common;
        $remark = $request->remark;

        foreach ($ids as $index => $id) {
            $stock = $stocks->find($id);
            $stock->whereId($id)->update([
                'balance' => $balance[$index],
                'annual_usage' => $annualUsage[$index],
                'common_name' => $common[$index],
                'remark' => $remark[$index],
            ]);
        }

        return auth()->user()->stock()->get()->sortBy('name');
    }

    public function fetchImport($request)
    {
        $importedData = Excel::toArray(new StockImport, $request->file);
        $data = collect($importedData)->collapse();

        if ($data) {
            $hospTemplate = collect(['item_code', 'drug_non_drug_name', 'packaging_description', 'total_stock_in_sku']);
            $ownTemplate = collect(['item_code', 'name', 'common_name', 'description', 'annual_usage', 'balance', 'remark', 'supplier']);
            $headers = collect(array_keys($data[0]));

            if ($headers->count() > 10) {
                $columns = $hospTemplate;
                $sheet = 'hosp';
            } else {
                $columns = $ownTemplate;
                $sheet = 'own';
            }
            if ($columns->diff($headers)->isNotEmpty()) {
                return false;
            }

            $this->importService($data, $sheet);
            Session::flash('message', 'Upload Successfully.');
            Session::flash('alert-class', 'alert-success');
            return auth()->user()->stock()->get()->sortBy('name');
        } else {
            Session::flash('message', 'File not uploaded.');
            Session::flash('alert-class', 'alert-danger');
            return;
        }
    }

    public function importService($data, $sheet)
    {
        $user = auth()->user();
        foreach ($data as $row) {
            if ($row['item_code']) {
                if ($sheet == 'hosp') {
                    $user->stock()->updateOrCreate(
                        [
                            'code' => $row['item_code'] ? $row['item_code'] : 'null',
                        ],
                        [
                            'name' => $row['drug_non_drug_name'] ? $row['drug_non_drug_name'] : 'null',
                            'description' => $row['packaging_description'] ? $row['packaging_description'] : '',
                            'balance' => $row['total_stock_in_sku'] ? $row['total_stock_in_sku'] : 0
                        ]
                    );
                } else {
                    $stock = $user->stock()->updateOrCreate(
                        [
                            'code' => $row['item_code'] ? $row['item_code'] : 'null',
                        ],
                        [
                            'name' => $row['name'] ? $row['name'] : 'null',
                            'common_name' => $row['common_name'] ? $row['common_name'] : '',
                            'description' => $row['description'] ? $row['description'] : '',
                            'annual_usage' => $row['annual_usage'] ? $row['annual_usage'] : 0,
                            'balance' => $row['balance'] ? $row['balance'] : 0,
                            'remark' => $row['remark'] ? $row['remark'] : '',
                        ]
                    );
                    if ($row['supplier'] && $stock) {
                        collect(explode(',', $row['supplier']))->map(function ($supplier) use ($stock) {
                            Supplier::updateOrCreate([
                                'stock_id' => $stock->id,
                                'name' => $supplier
                            ]);
                        });
                    }
                }
            }
        }
    }
}
