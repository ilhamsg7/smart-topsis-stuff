<?php

namespace App\Imports;

use App\Models\Combine;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function __construct() {
        ini_set('max_execution_time', 300);
    }

    public function model(array $row) {
        return new Combine([
            'alternative_id'    => $row['alternative_id'],
            'criterion_id'      => $row['criterion_id'],
            'value'             => $row['value'],
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return back()->with('success', 'Data berhasil diimport!');
    }
}
