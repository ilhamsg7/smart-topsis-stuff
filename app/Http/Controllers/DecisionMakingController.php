<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Imports\DataImport;
use App\Models\Alternative;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SmartTopsis;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;

class DecisionMakingController extends Controller
{
    public function topsis()
    {
        // Get the criteria from the database
        $criteria = Criterion::all();

        // Get the weight of each criterion and sum of all weights
        $criteriaWeights = $criteria->pluck('weight')->toArray();
        $criteriaSum = array_sum($criteriaWeights);

        // Normalize the decision matrix
        $candidates = Alternative::all();
        foreach ($candidates as $candidate) {
            $score = [];
            foreach ($criteria as $criterion) {
                $value = $candidate->{$criterion->name};
                $max = $criterion->max;
                $min = $criterion->min;
                $score[] = ($criterion->isBenefit) ? ($value - $min) / ($max - $min) : ($max - $value) / ($max - $min) ?? 0;
            }
            $candidate->score = $score;
        }
        return response()->json($candidates);
    }


    public function index()
    {
        $criteria = Criterion::all();
        $alternatives = Alternative::all();

        return view('criteria.index', compact('criteria', 'alternatives'));
    }

    public function import()
    {
        Excel::import(new DataImport, request()->file('file'));
        return back();
    }

    public function calculate(Request $request)
    {
        // Get alternative and criteria data from database
        $alternatives = Alternative::with('topsisSmart.criteria')->get();
        $criteria = Criterion::all();

        // Calculate TOPSIS-SMART matrix
        $matrix = SmartTopsis::makeMatrix($alternatives, $criteria, 'value', 'weight');
        $result_matrix = SmartTopsis::topsis($matrix);
        $prefer_score_collection = SmartTopsis::getPreferScoreCollection($result_matrix)->values();

        // Calculate rank
        $result = array_map(function ($item) use ($prefer_score_collection) {
            $item['rank'] = $prefer_score_collection->search($item['score']) + 1;
            return $item;
        }, $result_matrix);

        // Save calculation result to database
        foreach ($result as $item) {
            Alternative::find($item['alternative_id'])->update([
                'rank' => $item['rank'],
                'score' => $item['score'],
            ]);
        }

        return redirect()->route('decision-making.index')->with([
            'message' => 'Calculation completed successfully!',
            'alert-type' => 'success'
        ]);
    }
}
