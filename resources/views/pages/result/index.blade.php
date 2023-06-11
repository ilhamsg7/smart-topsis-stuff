@extends('layouts.app')

@php
    $module = Str::before(Route::currentRouteName(), '.');
@endphp

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.2/css/select.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row mb-3 mt-2">
            <h3>Step 1: Normalisasi Matriks</h3>
            <div class="table-responsive mt-2">
                <table class="table table-hover table-bordered" id="criteria_table">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            @foreach ($criteria as $criterion)
                                <th>{{ $criterion->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatives as $alternative)
                            <tr>
                                <td>{{ $alternative->name }}</td>
                                @foreach ($normalizedMatrix[$alternative->id] as $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            <h3>Step 2: Pembobotan Kriteria</h3>
            <div class="table-responsive mt-2">
                <table class="table table-hover table-bordered" id="criteria_table">
                    <thead>
                        <tr>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Bobot Ternormalisasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($criteria as $criterion)
                            <tr>
                                <td>{{ $criterion->name }}</td>
                                <td>{{ $criterion->weight }}</td>
                                <td>{{ $criterion->normalizedWeight }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            <h3>Step 3: Matriks Pembagi</h3>
            <div class="table-responsive mt-2">
                <table class="table table-hover table-bordered" id="criteria_table">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            @foreach ($criteria as $criterion)
                                <th>{{ $criterion->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatives as $alternative)
                            <tr>
                                <td>{{ $alternative->name }}</td>
                                @foreach ($divideMatrix[$alternative->id] as $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            <h3>Step 4: Matriks Normalisasi Bobot</h3>
            <div class="table-responsive mt-2">
                <table class="table table-hover table-bordered" id="criteria_table">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            @foreach ($criteria as $criterion)
                                <th>{{ $criterion->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatives as $alternative)
                            <tr>
                                <td>{{ $alternative->name }}</td>
                                @foreach ($normalizedWeight[$alternative->id] as $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            <h3>Step 5: Solusi Ideal Positif dan Negatif</h3>
            <div class="table-responsive mt-2">
                <table class="table table-hover table-bordered" id="criteria_table">
                    <thead>
                        <tr>
                            <th>Nama Kriteria</th>
                            <th>A+</th>
                            <th>A-</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($criteria as $i => $criterion)
                            <tr>
                                <td>{{ $criterion->name }}</td>
                                <td>{{ $idealPositive[$i] }}</td>
                                <td>{{ $idealNegative[$i] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            <h3>Step 6: Jarak Solusi Alternatif Positif dan Negatif</h3>
            <div class="table-responsive mt-2">
                <table class="table table-hover table-bordered" id="criteria_table">
                    <thead>
                        <tr>
                            <th>Nama Alternatif</th>
                            <th>D+</th>
                            <th>D-</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatives as $alternative)
                            <tr>
                                <td>{{ $alternative->name }}</td>
                                <td>{{ $positiveDistances[$alternative->id] }}</td>
                                <td>{{ $negativeDistances[$alternative->id] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="row mb-3">
            <h3>Step 7: Nilai Preferensi (V)</h3>
            <div class="table-responsive mt-2">
                <table class="table table-hover table-bordered" id="criteria_table">
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>ID</th>
                            <th>Nama Alternatif</th>
                            <th>Nilai Preferensi (V)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sortedPreferences = collect($preferences)
                                ->sort()
                                ->reverse();
                        @endphp
                        @foreach ($sortedPreferences as $alternativeId => $preference)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $alternativeId }}</td>
                                <td>{{ $alternatives->find($alternativeId)->name }}</td>
                                <td>{{ $preference }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
