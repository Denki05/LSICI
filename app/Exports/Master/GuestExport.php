<?php

namespace App\Exports\Master;

use App\Models\Guest;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class GuestExport implements FromArray, WithHeadings, WithEvents, WithCustomStartCell, WithDrawings
{
    protected $guests;

    public function __construct()
    {
        $this->guests = Guest::select('name', 'email', 'phone', 'company', 'photo')->get();
    }

    public function array(): array
    {
        return $this->guests->map(function ($guest) {
            return [
                $guest->name,
                $guest->email,
                $guest->phone,
                $guest->company,
                '', // kolom foto diisi lewat drawing
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Phone', 'Company', 'Photo'];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Judul di A1
                $event->sheet->setCellValue('A1', 'GUEST BOOK');
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                ]);

                // Atur tinggi baris sesuai gambar
                for ($i = 3; $i < 3 + count($this->guests); $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(80);
                }
            }
        ];
    }

    public function drawings(): array
    {
        $drawings = [];

        foreach ($this->guests as $index => $guest) {
            if (!$guest->photo) continue;

            $photoPath = public_path($guest->photo);
            if (!file_exists($photoPath)) continue;

            $drawing = new Drawing();
            $drawing->setName($guest->name);
            $drawing->setDescription('Guest Photo');
            $drawing->setPath($photoPath);
            $drawing->setHeight(70);

            // Data dimulai dari baris ke-3 (A2 adalah heading)
            $row = 3 + $index;
            $drawing->setCoordinates("E{$row}");

            $drawings[] = $drawing;
        }

        return $drawings;
    }
}