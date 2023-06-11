<?php

namespace App\Imports;

use App\Models\Criterion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CriteriaImport implements ToModel, WithHeadingRow
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
        return new Criterion([
            'name'              => $row['name'],
            'weight'            => $row['weight'],
            'normalization'     => $row['normalization'] ?? 0,
            'type'              => $row['type'],
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        $totalData = Criterion::all();
        $sumData = $totalData->sum('weight');
        $totalData->each(function ($item) use ($sumData) {
            $item->normalization = $item->weight / $sumData;
            $item->save();
        });
        return back()->with('success', 'Data berhasil diimport!');
    }
}
