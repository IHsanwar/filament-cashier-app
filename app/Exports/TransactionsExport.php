<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

use Maatwebsite\Excel\Concerns\WithHeadings;
class TransactionsExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::withCount('items')
            ->with(['items.product'])
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'total' => $transaction->total,
                    'date' => $transaction->created_at,
                    'items_count' => $transaction->items_count,
                    
                    
                ];
            });
    }
    

public function headings(): array
{
    return [
        'ID',
        'Date',
        'Total',
        'Items Count',
        
    ];
}

public function styles(Worksheet $sheet)
{
    foreach (range('A', 'D') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    for ($row = 2; $row <= 100; $row++) {
        if ($row % 2 == 0) {
            $sheet->getStyle("A{$row}:D{$row}")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F3F4F6'); // Gray-100 for even rows
        }
    }

    return [
        // Header row styling
        1 => [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo-600
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ],
        
        // Odd rows (default light background)
        'A2:D100' => [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F9FAFB'], // Light gray background (gray-50)
            ],
        ],
        
        // Date column styling
        'B2:B100' => [
            'numberFormat' => [
                'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_DDMMYYYY,
            ],
        ],
        
        'C2:C100' => [
            'numberFormat' => [
                'formatCode' => '#,##0.00',
            ],
        ],
        
        // Borders for all used cells
        'A1:D100' => [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'], // Gray-300 for subtle borders
                ],
            ],
        ],
        
        // Stronger outer border for the whole table
        'A1:D100' => [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '6B7280'], // Gray-500 for outer border
                ],
            ],
        ],
    ];

}
}
