<?php

namespace App\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;

class ReportExport implements
    FromCollection,
    WithHeadings,
    WithBatchInserts,
    WithChunkReading,
    WithMapping,
    ShouldQueue,
    WithStyles,
    WithColumnWidths,
    WithDefaultStyles
{
    use Exportable;

    protected $stocks;

    public function __construct($stocks)
    {
        $this->stocks = $stocks;
        $this->row = $stocks->count() + 3;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->stocks;
    }

    public function headings(): array
    {
        $startDate = session()->get('start_date');
        $endDate = session()->get('closing_date');

        return [
            [
                'For general item:',
                ' ',
                "Please specify the following info:\n1. Packaging\n2. Brand\n3. MDA Registration No.\n4. Ready stock?\n5. Contact No. PIC",
                ' ',
                "1. Please do not supply product with expiry date less than 6 months.\n2. LOU must be given if the product expiry date is less than 1 year upon receive of product.",
                ' ',
                ' ',
                ' ',
                ' '
            ],
            [
                "Date: $startDate",
                ' ',
                ' ',
                ' ',
                "Closing Date: $endDate",
                ' ',
                ' ',
                ' '
            ],
            [
                'No.',
                'Item Code',
                'Name',
                'Common Name',
                'Quantity',
                'Description',
                'Supplier',
                'Remark',
                'Status',
            ]
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
        $stock = (object) $stock;
        $suppliers = $stock->supplier->pluck('name')->map(function ($supplier, $key) {
            return ($key + 1 . '.' . $supplier);
        })->implode("\n");

        $qty = $stock->annual_usage - $stock->balance;
        $qty <= 0 ? $quantity = 0 : $quantity = $qty;

        return [
            $stock->id,
            $stock->code,
            $stock->name,
            $stock->common_name,
            $quantity,
            $stock->description,
            $suppliers,
            $stock->remark,
            $stock->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('C1:D1');
        $sheet->mergeCells('E1:I1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('E2:I2');
        $sheet->getStyle("A1:I$this->row")->getAlignment()->setWrapText(true);
        $sheet->getRowDimension('1')->setRowHeight(150);
        $sheet->getRowDimension('2')->setRowHeight(25);
        $sheet->getRowDimension('3')->setRowHeight(25);

        // to set header shown on each page
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 3);

        // to set paper size
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);

        // to setup pdf fit in one page
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageMargins()->setTop(1);
        $sheet->getPageMargins()->setRight(0.75);
        $sheet->getPageMargins()->setLeft(0.75);
        $sheet->getPageMargins()->setBottom(1);

        for ($i = 1; $i <= $this->row; $i++) {
            // to make alternate row different colour
            if ($i > 2 && $i % 2 != 0) {
                $sheet->getStyle('A' . $i . ':I' . $i)->applyFromArray(
                    [
                        'fill' => [
                            'fillType' => 'solid',
                            'rotation' => 0,
                            'color' => ['rgb' => 'D9D9D9']
                        ],
                    ]
                );
            }
            // to make all cells bordered
            $sheet->getStyle('A' . $i . ':I' . $i)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '0000000'],
                    ],
                ],
            ]);
        }

        return [
            3 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => 'solid',
                    'rotation' => 0,
                    'color' => ['rgb' => 'A9A9A9']
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 40,
            'D' => 40,
            'E' => 15,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 10,
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        // Configure the default styles
        return [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'font' => ['name' => 'Arial', 'size' => 12],
        ];
    }
}
