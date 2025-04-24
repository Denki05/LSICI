<?php

namespace App\Exports\Master;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RsvpExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Name' => $item->name,
                'Officer' => $item->officer,
                'Category' => $item->category,
                'Attendance' => $item->attendance == 1 ? 'YES' : 'NO',
                'Link' => route('admin.rsvp.page', base64_encode($item->slug)),
            ];
        });
    }

    public function headings(): array
    {
        return ['Name', 'Officer', 'Category', 'Attendance', 'Link'];
    }
}