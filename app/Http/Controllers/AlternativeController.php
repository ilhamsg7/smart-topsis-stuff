<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use Illuminate\Http\Request;
use App\Imports\AlternativeImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\AlternativeRequest;

class AlternativeController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Alternative::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $row = [
                        'id' => $row->id,
                        'name' => $row->name,
                    ];
                    return view('components.column.action-column-alternative', compact('row'));
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.alternatives.index', [
            'active' => 'alternatives'
        ]);
    }

    public function store(AlternativeRequest $request) {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            Alternative::create($data);
            DB::commit();
            return redirect()->back()->with('success', 'Alternatif berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('failed', 'Alternatif gagal ditambahkan');
        }
    }

    public function show(Alternative $alternative) {
        $data = Alternative::findOrFail($alternative->id);
        return $data;
    }

    public function destroy(Alternative $alternative) {
        $data = Alternative::findOrFail($alternative->id);
        $data->delete();
        return redirect()->back()->with('success', 'Alternatif berhasil dihapus');
    }

    public function update(AlternativeRequest $request, Alternative $alternative) {
        try {
            DB::beginTransaction();
                $data = $request->validated();
                Alternative::where('id', $alternative->id)->update($data);
            DB::commit();
            return redirect()->back()->with('success', 'Alternatif berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('failed', 'Alternatif gagal diubah');
        }
    }

    public function import()
    {
        Excel::import(new AlternativeImport, request()->file('file'));
        return back();
    }
}
