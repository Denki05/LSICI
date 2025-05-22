<?php

namespace App\Imports\Master;

use App\Models\Guest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;

class GuestImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        $rows = $collection->skip(1);

        foreach ($rows as $index => $row) {
            if (!isset($row[0])) {
                continue;
            }

            $rawDate = $row[4] ?? null;
            $parsedDate = $this->parseExcelDate($rawDate);

            // Logging untuk melihat nilai mentah dan hasil parsing
            Log::info("Import Row #{$index}", [
                'name'        => $row[0],
                'raw_date'    => $rawDate,
                'parsed_date' => $parsedDate,
                'parsed_type' => gettype($parsedDate),
            ]);

            Guest::create([
                'name'       => $row[0],
                'email'      => $row[1] ?? null,
                'phone'      => $row[2] ?? null,
                'company'    => $row[3] ?? null,
                'created_at' => $parsedDate,
            ]);
        }
    }

    private function parseExcelDate($value)
    {
        // Jika numeric (serial date)
        if (is_numeric($value)) {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value));
            } catch (\Exception $e) {
                Log::error('Gagal konversi Excel serial date', ['value' => $value]);
                return now();
            }
        }

        // Jika string format 'd/m/Y'
        try {
            return Carbon::createFromFormat('d/m/Y', $value);
        } catch (\Exception $e) {
            // Fallback to auto-parse
            try {
                return Carbon::parse($value);
            } catch (\Exception $e) {
                Log::error('Gagal parse tanggal string', ['value' => $value]);
                return now();
            }
        }
    }
}