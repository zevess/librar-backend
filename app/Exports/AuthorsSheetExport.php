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

class AuthorsSheetExport implements FromCollection, WithMapping, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
     * Create a new class instance.
     */

    protected $authors;
    protected $startDate;
    protected $endDate;
    public function __construct($authors, $startDate, $endDate)
    {
        $this->authors = $authors;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->authors;
    }

    public function title(): string
    {
        return 'Авторы';
    }

    public function map($author): array
    {
        return [
            $author['author_id'],
            $author['author_name'],
            $author['count'],
        ];
    }

    public function headings(): array
    {
        return [
            ["Брони книг авторов с {$this->startDate} по {$this->endDate}"],
            [],
            [
                'ID автора',
                'Имя автора',
                'Количество броней книг автора'
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
