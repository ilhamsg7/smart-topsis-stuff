<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use Illuminate\Http\Request;
use App\Imports\CriteriaImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\CriteriaRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CriterionController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Criterion::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action',
                function($row) {
                    $row = [
                        'id' => $row->id,
                        'name' => $row->name,
                        'weight' => $row->weight,
                        'type' => $row->type,
                        'normalization' => $row->normalization,
                    ];
                    return view('components.column.action-column', compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.criteria.index', [
            'active' => 'criteria'
        ]);
    }

    public function store(CriteriaRequest $request) {
        try {
            DB::beginTransaction();
                $data = $request->validated();
                Criterion::create($data);
                $totalData = Criterion::all();
                $sumData = $totalData->sum('weight');
                $totalData->each(function($item) use ($sumData) {
                    $item->normalization = $item->weight / $sumData;
                    $item->save();
                });
            DB::commit();
            return redirect()->back()->with('success', 'Kriteria berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('failed', 'Kriteria gagal ditambahkan');
        }
    }

    public function show(Criterion $criterion)
    {
        $data = Criterion::findOrFail($criterion->id);
        return $data;
    }

    public function update(CriteriaRequest $request, Criterion $criterion)
    {
        try {
            DB::beginTransaction();
                $data = $request->validated();
                Criterion::where('id', $criterion->id)->update($data);
                $totalData = Criterion::all();
                $sumData = $totalData->sum('weight');
                $totalData->each(function($item) use ($sumData) {
                    $item->normalization = $item->weight / $sumData;
                    $item->save();
                });
            DB::commit();
            return redirect()->back()->with('success', 'Kriteria berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('failed', 'Kriteria gagal diubah');
        }
    }

    public function import()
    {
        Excel::import(new CriteriaImport, request()->file('file'));
        return back();
    }

    public function destroy(Criterion $criterion)
    {
        try {
            DB::beginTransaction();
                Criterion::destroy($criterion->id);
                $sumData = Criterion::sum('weight');
                $data = Criterion::all();
                foreach ($data as $key => $value) {
                    $value->normalization = $value->weight / $sumData;
                    $value->save();
                }
            DB::commit();
            return redirect()->back()->with('success', 'Kriteria berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('failed', 'Kriteria gagal dihapus');
        }
    }
}
