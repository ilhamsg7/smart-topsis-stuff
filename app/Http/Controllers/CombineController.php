<?php

namespace App\Http\Controllers;

use App\Models\Combine;
use App\Models\Criterion;
use App\Imports\DataImport;
use App\Models\Alternative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;

class CombineController extends Controller
{
    public function index()
    {
        $column = Criterion::all();
        $combine = Combine::all();
        $alternativeNames = Alternative::pluck('name', 'id');
        $minMaxValues = Combine::select('criterion_id', DB::raw('MIN(value) as min_value'), DB::raw('MAX(value) as max_value'))
            ->groupBy('criterion_id')
            ->get();
        return view('pages.combine.index', [
            'column' => $column ?? null,
            'active' => 'combine',
            'data' => $combine ?? null,
            'alternativeNames' => $alternativeNames ?? null,
            'minMaxValues' => $minMaxValues ?? null
        ]);

        // // Ambil data alternatif
        // $alternatives = Alternative::all();

        // // Ambil data kriteria
        // $criteria = Criterion::all();

        // // Ambil data combine
        // $combines = Combine::with(['alternative', 'criterion'])->get();
        // $minMaxValues = Combine::select('criterion_id', DB::raw('MIN(value) as min_value'), DB::raw('MAX(value) as max_value'))
        //     ->groupBy('criterion_id')
        //     ->get();

        // // Hitung bobot tiap kriteria
        // $totalWeight = $criteria->sum('weight');
        // $criteria->each(function ($criterion) use ($totalWeight) {
        //     $criterion->normalizedWeight = $criterion->weight / $totalWeight;
        // });

        // // Hitung matriks ternormalisasi
        // foreach ($alternatives as $alternative) {
        //     $alternativeValues = $combines->where('alternative_id', $alternative->id)
        //         ->pluck('value', 'criterion_id');
        //     $alternativeNormalizedValues = [];
        //     foreach ($criteria as $criterion) {
        //         $value = $alternativeValues[$criterion->id] ?? 0;
        //         $alternativeNormalizedValues[] = $value / $minMaxValues->firstWhere('criterion_id', $criterion->id)->max_value;
        //     }
        //     $normalizedMatrix[$alternative->id] = $alternativeNormalizedValues;
        // }

        // // Hitung matriks terbobot
        // $weightedMatrix = [];
        // foreach ($alternatives as $alternative) {
        //     $alternativeNormalizedValues = $normalizedMatrix[$alternative->id];
        //     $alternativeWeightedValues = [];
        //     foreach ($criteria as $i => $criterion) {
        //         $alternativeWeightedValues[] = $alternativeNormalizedValues[$i] * $criterion->normalizedWeight;
        //     }
        //     $weightedMatrix[$alternative->id] = $alternativeWeightedValues;
        // }

        // // Hitung solusi ideal positif (A+)
        // $idealPositive = [];
        // foreach ($criteria as $i => $criterion) {
        //     $idealPositive[$i] = $criterion->type == 'benefit' ? max(array_column($weightedMatrix, $i)) : min(array_column($weightedMatrix, $i));
        // }

        // // Hitung solusi ideal negatif (A-)
        // $idealNegative = [];
        // foreach ($criteria as $i => $criterion) {
        //     $idealNegative[$i] = $criterion->type == 'benefit' ? min(array_column($weightedMatrix, $i)) : max(array_column($weightedMatrix, $i));
        // }

        // // Hitung jarak solusi alternatif positif (D+)
        // $positiveDistances = [];
        // foreach ($alternatives as $alternative) {
        //     $alternativeWeightedValues = $weightedMatrix[$alternative->id];
        //     $distance = 0;
        //     foreach ($criteria as $i => $criterion) {
        //         $distance += pow($alternativeWeightedValues[$i] - $idealPositive[$i], 2);
        //     }
        //     $positiveDistances[$alternative->id] = sqrt($distance);
        // }

        // // Hitung jarak solusi alternatif negatif (D-)
        // $negativeDistances = [];
        // foreach ($alternatives as $alternative) {
        //     $alternativeWeightedValues = $weightedMatrix[$alternative->id];
        //     $distance = 0;
        //     foreach ($criteria as $i => $criterion) {
        //         $distance += pow($alternativeWeightedValues[$i] - $idealNegative[$i], 2);
        //     }
        //     $negativeDistances[$alternative->id] = sqrt($distance);
        // }

        // // Hitung nilai preferensi (V)
        // $preferences = [];
        // foreach ($alternatives as $alternative) {
        //     $positiveDistance = $positiveDistances[$alternative->id];
        //     $negativeDistance = $negativeDistances[$alternative->id];
        //     $preferences[$alternative->id] = $negativeDistance / ($positiveDistance + $negativeDistance);
        // }
        // return response()->json([
        //     'alternatives' => $alternatives,
        //     'criteria' => $criteria,
        //     'combines' => $combines,
        //     'normalizedMatrix' => $normalizedMatrix,
        //     'weightedMatrix' => $weightedMatrix,
        //     'idealPositive' => $idealPositive,
        //     'idealNegative' => $idealNegative,
        //     'positiveDistances' => $positiveDistances,
        //     'negativeDistances' => $negativeDistances,
        //     'preferences' => $preferences
        // ]);
        // return view('topsis.index', compact(
        //     'alternatives',
        //     'criteria',
        //     'combines',
        //     'normalizedMatrix',
        //     'weightedMatrix',
        //     'idealPositive',
        //     'idealNegative',
        //     'positiveDistances',
        //     'negativeDistances',
        //     'preferences'
        // ));
    }

    public function import()
    {
        Excel::import(new DataImport, request()->file('file'));
        return back()->with('success', 'Data berhasil diimport!');
    }
}
