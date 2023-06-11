<?php

namespace App\Imports;

use App\Models\Alternative;
use App\Models\Criterion;
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
        new Alternative([
            'name'     => $row['name'],
        ]);

        new Criterion([
            'name'     => $row['name'],
            'weight'     => $row['weight'],
        ]);

        return back()->with('importSuccess', 'Data berhasil diimport!');
    }
}
