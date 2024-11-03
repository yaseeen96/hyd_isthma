@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'CheckInOut Entires'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            CheckInOut Entires
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <button class="btn btn-purple float-right" type="button" data-toggle="collapse" data-target="#regFilters"
                        aria-expanded="false" aria-controls="regFilters">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="btn btn-purple float-right mr-2" onclick="clearFilters()"> <i class="fas fa-filter "></i>
                        Clear
                        Filters</button>
                    <a href="{{ route('checkInOutEntries.index') }}" class="btn btn-purple float-right mr-2"
                        onclick="clearFilters()"> <i class="fas fa-file "></i>
                        Main Report</a>
                </div>
                <div class="collapse container" id="regFilters">
                    <div class="card card-body shadow-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PLACE</label>
                                    <select name="place_id" id="place_id" class="form-control select2bs4"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        @foreach ($checkInOutPlaces as $place)
                                            <option value="{{ $place->id }}">{{ $place->place_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mode</label>
                                    <select name="mode" id="mode" class="form-control select2bs4"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        <option value="entry">Entry</option>
                                        <option value="exit">Exit</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="checkinout-entires">
                <th>SL.No </th>
                <th>Batch Type</th>
                <th>Batch ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Zone</th>
                <th>Division </th>
                <th>Unit</th>
                <th>Place</th>
                <th>Date&Time</th>
                <th>Mode</th>
                <th>Scanned By</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        function clearFilters() {
            $('#place_id').val('').trigger('change');
            $('#mode').val('').trigger('change');
            setFilter();
        }
        $('.date_time').on('change.datetimepicker', function() {
            setFilter();
        })
        $('.time').on('change.datetimepicker', function() {
            setFilter();
        })
        const checkInOutEntiresTable = $("#checkinout-entires").DataTable({
            ajax: {
                url: "{{ route('position-report') }}",
                data: function(d) {
                    d.place_id = $("#place_id").val();
                    d.mode = $("#mode").val();
                }
            },
            columns: [
                dtIndexCol(),
                {
                    data: 'batch_type',
                },
                {
                    data: 'batch_id',
                },
                {
                    data: 'name'
                },
                {
                    data: 'gender',
                },
                {
                    data: 'phone_number'
                },
                {
                    data: 'zone_name'
                },
                {
                    data: 'division_name'
                },
                {
                    data: 'unit_name'
                },
                {
                    data: 'place'
                },
                {
                    data: 'datetime',
                    searchable: false
                },
                {
                    data: 'mode'
                },
                {
                    data: 'user'
                },
            ]
        })

        function setFilter() {
            checkInOutEntiresTable.draw();
        }
    </script>
@endpush
