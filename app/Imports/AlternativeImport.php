<?php

namespace App\Imports;

use App\Models\Alternative;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlternativeImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    public function __construct()
    {
        ini_set('max_execution_time', 300);
    }

    public function model(array $row)
    {
        return new Alternative([
            'name'     => $row['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('importSuccess', 'Data berhasil diimport!');
    }
}
