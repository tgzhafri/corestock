<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Protection;

class StockExport implements
    FromCollection,
    WithHeadings,
    WithBatchInserts,
    WithChunkReading,
    WithMapping,
    ShouldQueue,
    ShouldAutoSize,
    WithStyles,
    WithEvents
{
    use Exportable, RegistersEventListeners;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return auth()->user()->stock()->get()->sortBy('name');
    }

    public function headings(): array
    {
        return [
            'id',
            'item_code',
            'name',
            'common_name',
            'description',
            'annual_usage',
            'balance',
            'remark',
            'supplier',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function map($stock): array
    {
        return [
            $stock->id,
            $stock->code,
            $stock->name,
            $stock->common_name,
            $stock->description,
            $stock->annual_usage,
            $stock->balance,
            $stock->remark,
            $stock->supplier->pluck('name')->implode(','),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '0000000'],
                ],
            ],
            'font' => ['bold' => true, 'size' => 12]
        ];

        $sheet->getStyle('A1:I1')->getFill()->applyFromArray(['fillType' => 'solid', 'rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
        $sheet->getStyle('A1:I1')->applyFromArray($styleArray);

        $sheet->getProtection()->setPassword('password');
        $sheet->getProtection()->setSheet(true);
        $sheet->getStyle('B2:I300')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->getDelegate()->getComment('I1')->getText()->createTextRun('Insert multiple suppliers (i.e. Supplier 1, Supplier 2, Supplier 3');
        $event->sheet->getDelegate()->getComment('A1')->getText()->createTextRun('You can add new item by leaving the id column blank');
    }
}
