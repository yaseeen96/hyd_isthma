@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'CheckInOut Entired'])
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
                </div>
                <div class="collapse container" id="regFilters">
                    <div class="card card-body shadow-none">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>ZONE NAME</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="zone_name"
                                        onchange="getLocations('zone_name', 'division_name')">
                                        @isset($locationsList['distnctZoneName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctZoneName'] as $name)
                                                <option value="{{ $name->zone_name }}"> {{ $name->zone_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>DISTRICT NAME</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="division_name"
                                        onchange="getLocations('division_name', 'unit_name')">
                                        <option value="">All</option>
                                        {{-- data will be dynamically filled --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>UNIT NAME</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="unit_name"
                                        placeholder="Select Unit Name" onchange="setFilter()">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Batch Types</label>
                                    <select name="batch_type" id="batch_type" class="form-control select2bs4"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        @foreach ($batchTypes as $key => $value)
                                            <option value="{{ $value }}">{{ Str::ucfirst($value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="text" name="date" id="date" class="form-control date_time"
                                        data-toggle="datetimepicker" data-target="#date_time" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>From Time</label>
                                    <input type="text" name="from_time" id="from_time" class="form-control time"
                                        data-toggle="datetimepicker" data-target="#time" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>To Time</label>
                                    <input type="text" name="to_time" id="to_time" class="form-control time"
                                        data-toggle="datetimepicker" data-target="#time" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Qr Operators</label>
                                    <select name="qr_operator" id="qr_operator" class="form-control select2bs4"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        @foreach ($qrOperators as $operator)
                                            <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                        @endforeach
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
            $('#zone_name').val('').trigger('change');
            $('#division_name').val('').trigger('change');
            $('#unit_name').val('').trigger('change');
            $('#place_id').val('').trigger('change');
            $('#mode').val('').trigger('change');
            $('#batch_type').val('').trigger('change');
            $('#date').val('');
            $('#from_time').val('');
            $('#to_time').val('');
            $('#qr_operator').val('').trigger('change');
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
                url: "{{ route('checkInOutEntries.index') }}",
                data: function(d) {
                    d.zone_name = $("#zone_name").val();
                    d.division_name = $("#division_name").val();
                    d.unit_name = $("#unit_name").val();
                    d.place_id = $("#place_id").val();
                    d.mode = $("#mode").val();
                    d.batch_type = $("#batch_type").val();
                    d.date = $("#date").val();
                    d.from_time = $("#from_time").val();
                    d.to_time = $("#to_time").val();
                    d.qr_operator = $("#qr_operator").val();
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
