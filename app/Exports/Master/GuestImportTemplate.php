<?php

namespace App\Exports\Master;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GuestImportTemplate implements FromArray, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'name', 
                'email',
                'phone',
                'company',
                'tanggal',
            ]
        ];
    }
}
