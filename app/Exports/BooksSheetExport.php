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

class BooksSheetExport implements FromCollection, WithMapping, WithTitle, WithStyles, WithHeadings, ShouldAutoSize
{
    /**
     * Create a new class instance.
     */

    protected $books;
    protected $startDate;
    protected $endDate;
    public function __construct($books, $startDate, $endDate)
    {
        $this->books = $books;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->books;
    }

    public function title(): string
    {
        return 'Книги';
    }

    public function map($book): array
    {
        return [
            $book['book_id'],
            $book['title'],
            $book['slug'],
            $book['count'],
        ];
    }
    public function headings(): array
    {
        return [
            ["Брони книг с {$this->startDate} по {$this->endDate}"],
            [],
            [
                'ID книги',
                'Название книги',
                'Slug книги',
                'Количество броней книги',
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D2');

        return [
            'A1:D3' => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFD1EBE9'],
                ],
            ],
            'A3:D3' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ],
            ],
            'A1:D2' => [
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
            'D' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ]
        ];
    }
}
