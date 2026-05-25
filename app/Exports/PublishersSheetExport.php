<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PublishersSheetExport implements FromCollection, WithMapping, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * Create a new class instance.
     */

    protected $publishers;
    protected $startDate;
    protected $endDate;
    public function __construct($publishers, $startDate, $endDate)
    {
        $this->publishers = $publishers;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->publishers;
    }

    public function title(): string
    {
        return 'Издательства';
    }

    public function map($publisher): array
    {
        return [
            $publisher['publisher_id'],
            $publisher['publisher_name'],
            $publisher['count'],
        ];
    }
    public function headings(): array
    {
        return [
            ["Брони издательств с {$this->startDate} по {$this->endDate}"],
            [],
            [
                'ID издательства',
                'Название издательства',
                'Количество броней книг издательства'
            ],
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:C2');

        return [
            'A1:C3' => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD1EBE9'],
                ],
            ],
            'A3:C3' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ],
            ],
            'A1:C2' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ],
            ],
            'B:C' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ]
            ],
            'A' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],
            'C' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ]
        ];
    }
}
