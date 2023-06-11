<?php

namespace App\Http\Controllers;

use App\Models\Combine;
use App\Models\Criterion;
use App\Imports\DataImport;
use App\Models\Alternative;
use App\Models\SmartTopsis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;

class DecisionMakingController extends Controller
{

    public function index()
    {
        // Ambil data alternatif
        $alternatives = Alternative::all();

        // Ambil data kriteria
        $criteria = Criterion::all();

        // Ambil data combine
        $combines = Combine::with(['alternative', 'criterion'])->get();
        $minMaxValues = Combine::select('criterion_id', DB::raw('MIN(value) as min_value'), DB::raw('MAX(value) as max_value'))
            ->groupBy('criterion_id')
            ->get();

        // Hitung bobot tiap kriteria
        $totalWeight = $criteria->sum('weight');
        $criteria->each(function ($criterion) use ($totalWeight) {
            $criterion->normalizedWeight = $criterion->weight / $totalWeight;
        });

        // Hitung matriks ternormalisasi
        $normalizedMatrix = [];
        foreach ($alternatives as $alternative) {
            $alternativeValues = $combines->where('alternative_id', $alternative->id)
                ->pluck('value', 'criterion_id');
            $alternativeNormalizedValues = [];
            foreach ($criteria as $criterion) {
                $value = $alternativeValues[$criterion->id] ?? 0;
                if ($criterion->type == 'benefit') {
                    $alternativeNormalizedValues[] =
                        (
                            ($value - $minMaxValues->firstWhere('criterion_id', $criterion->id)->min_value) /
                            ($minMaxValues->firstWhere('criterion_id', $criterion->id)->max_value - $minMaxValues->firstWhere('criterion_id', $criterion->id)->min_value)
                        ) * 100;
                } else {
                    $alternativeNormalizedValues[] =
                        (
                            ($minMaxValues->firstWhere('criterion_id', $criterion->id)->max_value - $value) /
                            ($minMaxValues->firstWhere('criterion_id', $criterion->id)->max_value - $minMaxValues->firstWhere('criterion_id', $criterion->id)->min_value)
                        ) * 100;
                }
            }
            $normalizedMatrix[$alternative->id] = $alternativeNormalizedValues;
        }

        // Jumlah total nilai tiap-tiap kriteria dari alternatif dengan mengambil nilaidari normalizedMatrix
        $sumCriteriaValues = [];
        foreach ($criteria as $i => $criterion) {
            $sumCriteriaValues[$i] = array_sum(array_column($normalizedMatrix, $i));
        }

        // Akar dari sumCriteriaValues
        $sqrtSumCriteriaValues = [];
        foreach ($sumCriteriaValues as $i => $sumCriteriaValue) {
            $sqrtSumCriteriaValues[$i] = sqrt($sumCriteriaValue);
        }

        // Pembagian nilai tiap-tiap kriteria dari alternatif dengan nilai akar dari sqrtSumCriteriaValues
        $divideMatrix = [];
        foreach ($alternatives as $alternative) {
            $alternativeNormalizedValues = $normalizedMatrix[$alternative->id];
            $alternativeDivideValues = [];
            foreach ($criteria as $i => $criterion) {
                $alternativeDivideValues[] = $alternativeNormalizedValues[$i] / $sqrtSumCriteriaValues[$i];
            }
            $divideMatrix[$alternative->id] = $alternativeDivideValues;
        }

        // Normalisasi pembobotan dengan membagi antara divideMatrix dengan weight pada criteria di setiap alternatif
        $normalizedWeight = [];
        foreach ($alternatives as $alternative) {
            $alternativeNormalizedValues = $divideMatrix[$alternative->id];
            $alternativeNormalizedWeight = [];
            foreach ($criteria as $i => $criterion) {
                $alternativeNormalizedWeight[] = $alternativeNormalizedValues[$i] / $criterion->normalizedWeight;
            }
            $normalizedWeight[$alternative->id] = $alternativeNormalizedWeight;
        }

        // Hitung solusi ideal positif (A+)
        $idealPositive = [];
        foreach ($criteria as $i => $criterion) {
            $idealPositive[$i] = $criterion->type === 'benefit' ? max(array_column($normalizedWeight, $i)) : min(array_column($normalizedWeight, $i));
        }

        // Hitung solusi ideal negatif (A-)
        $idealNegative = [];
        foreach ($criteria as $i => $criterion) {
            $idealNegative[$i] = $criterion->type === 'benefit' ? min(array_column($normalizedWeight, $i)) : max(array_column($normalizedWeight, $i));
        }

        // Hitung jarak solusi alternatif positif (D+)
        $positiveDistances = [];
        foreach ($alternatives as $alternative) {
            $alternativeWeightedValues = $normalizedWeight[$alternative->id];
            $distance = 0;
            foreach ($criteria as $i => $criterion) {
                $distance += pow($alternativeWeightedValues[$i] - $idealPositive[$i], 2);
            }
            $positiveDistances[$alternative->id] = $distance;
        }

        // Hitung jarak solusi alternatif negatif (D-)
        $negativeDistances = [];
        foreach ($alternatives as $alternative) {
            $alternativeWeightedValues = $normalizedWeight[$alternative->id];
            $distance = 0;
            foreach ($criteria as $i => $criterion) {
                $distance += pow($alternativeWeightedValues[$i] - $idealNegative[$i], 2);
            }
            $negativeDistances[$alternative->id] = $distance;
        }

        // Hitung nilai preferensi (V)
        $preferences = [];
        foreach ($alternatives as $alternative) {
            $positiveDistance = $positiveDistances[$alternative->id];
            $negativeDistance = $negativeDistances[$alternative->id];
            $preferences[$alternative->id] = $positiveDistance + $negativeDistance;
        }

        // return response()->json([
        //     'alternatives' => $alternatives,
        //     'criteria' => $criteria,
        //     'combines' => $combines,
        //     'normalizedMatrix' => $normalizedMatrix,
        //     'sumCriteriaValues' => $sumCriteriaValues,
        //     'sqrtSumCriteriaValues' => $sqrtSumCriteriaValues,
        //     'divideMatrix' => $divideMatrix,
        //     'normalizedWeight' => $normalizedWeight,
        //     'idealPositive' => $idealPositive,
        //     'idealNegative' => $idealNegative,
        //     'positiveDistances' => $positiveDistances,
        //     'negativeDistances' => $negativeDistances,
        //     'preferences' => $preferences
        // ]);

        return view('pages.result.index', [
            'active' => 'result',
            'alternatives' => $alternatives,
            'criteria' => $criteria,
            'combines' => $combines,
            'normalizedMatrix' => $normalizedMatrix,
            'sumCriteriaValues' => $sumCriteriaValues,
            'sqrtSumCriteriaValues' => $sqrtSumCriteriaValues,
            'divideMatrix' => $divideMatrix,
            'normalizedWeight' => $normalizedWeight,
            'idealPositive' => $idealPositive,
            'idealNegative' => $idealNegative,
            'positiveDistances' => $positiveDistances,
            'negativeDistances' => $negativeDistances,
            'preferences' => $preferences
        ]);
    }
}
