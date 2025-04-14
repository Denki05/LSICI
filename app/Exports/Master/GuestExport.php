<?php

namespace App\Exports\Master;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class GuestExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    public function collection()
    {
        return Guest::select('name', 'email', 'phone', 'company')->get();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Company'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Tambahkan judul di atas (A1)
                $event->sheet->setCellValue('A1', 'GUEST BOOK');

                // Merge cell A1 sampai D1 untuk judul
                $event->sheet->mergeCells('A1:D1');

                // Styling (opsional)
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }

    // Geser heading dan data ke bawah (karena A1 sudah dipakai untuk judul)
    public function startCell(): string
    {
        return 'A2';
    }
}