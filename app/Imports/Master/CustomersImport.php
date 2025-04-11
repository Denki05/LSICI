<?php

namespace App\Imports\Master;

use App\Models\Customer;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    protected $names;
    protected $namesWithCity;
    public $successData = [];
    public $failedData = [];

    public function __construct(array $names, array $namesWithCity)
    {
        $this->names = $names;
        $this->namesWithCity = $namesWithCity;
    }

    public function model(array $row)
    {
        $excelName = strtolower(trim($row['name'] ?? ''));
        $category = $row['category'] ?? null;

        // Sudah ada di database
        if (Customer::whereRaw('LOWER(name) = ?', [$excelName])->exists()) {
            $this->failedData[] = array_merge($row, [
                'error' => 'Nama sudah terdaftar di database'
            ]);
            return null;
        }

        // ✅ Cek apakah nama ada langsung di field `name` dari API
        if (in_array($excelName, $this->names)) {
            $this->successData[] = $row;

            return new Customer([
                'name'     => $row['name'],
                'category' => $category,
            ]);
        }

        // ✅ Jika tidak, cek apakah cocok sebagai `name + kota`
        if (in_array($excelName, $this->namesWithCity)) {
            $this->successData[] = $row;

            return new Customer([
                'name'     => $row['name'],
                'category' => $category,
            ]);
        }

        // ❌ Tidak cocok dengan data API
        $this->failedData[] = array_merge($row, [
            'error' => 'Nama tidak cocok dengan data API'
        ]);
        return null;
    }
}