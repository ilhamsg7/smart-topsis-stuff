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
    <div class="container mt-4">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible mt-3 mx-3 fade show py-3 px-3 position-fixed-alert"
                    role="alert">
                    {{ $error }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible mt-3 mx-3 fade show py-3 px-3 position-fixed-alert"
                role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('failed'))
            <div class="alert alert-danger alert-dismissible mt-3 mx-3 fade show py-3 px-3 position-fixed-alert"
                role="alert">
                {{ session('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Wishub Criteria</h3>
            </div>
        </div>
        <div class="row">
            <div class="d-flex gap-2 justify-content-end align-items-center mb-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fa-solid fa-plus"></i> Add Data
                </button>
                <form method="post" enctype="multipart/form-data" class="flex items-center col-span-3">
                    @csrf
                    <input type="file" id="file" name="file" required
                        class="text-sm rounded-l-md cursor-pointer">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                        <i class="fa-sharp fa-solid fa-arrow-down"></i> Import Excel
                    </button>
                </form>
            </div>
        </div>
        <div class="table-responsive mt-4">
            <table class="table table-hover table-bordered table-responsive data-table" id="criteria_table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Weight</th>
                        <th scope="col">Normalization</th>
                        <th scope="col">Type</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <x-form.modal-criteria />

        <form action="" method="POST">
            <x-modal.show-criteria modalId="showModal">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="editName"
                        name="name">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="weight" class="form-label">Weight</label>
                    <input type="number" class="form-control @error('weight') is-invalid @enderror" id="editWeight"
                        name="weight">
                    @error('weight')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="normalization" class="form-label">Normalization</label>
                    <input type="number" class="form-control @error('normalization') is-invalid @enderror" readonly
                        id="editNormalization" name="normalization">
                    @error('normalization')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="">Type</label>
                    <select name="type" id="editType" class="form-control @error('type') is-invalid @enderror">
                        <option value="benefit">Benefit</option>
                        <option value="cost">Cost</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </x-modal.show-criteria>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(function() {
            let table = $('#criteria_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('criteria.index') }}",
                },
                columns: [{
                        data: null,
                        "render": function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        defaultContent: '-',
                        render: function(data) {
                            return data ?? '-';
                        }
                    },
                    {
                        data: 'weight',
                        name: 'weight',
                        defaultContent: '-',
                        render: function(data) {
                            return data ?? '-';
                        }
                    },
                    {
                        data: 'normalization',
                        name: 'normalization',
                        defaultContent: '-',
                        render: function(data) {
                            return data ?? '-';
                        }
                    },
                    {
                        data: 'type',
                        name: 'type',
                        defaultContent: '-',
                        render: function(data) {
                            if (data == 'benefit') {
                                return 'Benefit';
                            } else {
                                return 'Cost';
                            }
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

        $(document).ready(function() {
            const routeName = "{{ route($module . '.index') }}";
            $('body').on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let weight = $(this).data('weight');
                let normalization = $(this).data('normalization');
                let type = $(this).data('type');

                $('#showModal').closest('form').attr('action', `${routeName}/${id}`);
                $('#editName').val(name);
                $('#editWeight').val(weight);
                $('#editNormalization').val(normalization);
                $('#editType').val(type).change();
            });
        });
    </script>
@endsection
