@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Count By Place'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Total Count By Place
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
                                    <label>User Type</label>
                                    <select name="category" id="category" class="form-control select2bs4"
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
                                    <label>Batch Type</label>
                                    <select name="batch_type" id="batch_type" class="form-control select2bs4"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        <option value="rukn">Rukn</option>
                                        <option value="nonRukn">Non Rukn</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="total-count-by-place">
                <th>SL.No </th>
                <th>Place Name</th>
                <th>Total Count</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        function clearFilters() {
            $('#mode').val('').trigger('change');
            $('#zone_name').val('').trigger('change');
            $('#division_name').val('').trigger('change');
            $('#unit_name').val('').trigger('change');
            $('#date').val('');
            $('#from_time').val('');
            $('#to_time').val('');
            $('#category').val('').trigger('change');
            $('#batch_type').val('').trigger('change');
            setFilter();
        }
        $('.date_time').on('change.datetimepicker', function() {
            setFilter();
        })
        $('.time').on('change.datetimepicker', function() {
            setFilter();
        })
        const totalCountByPlace = $("#total-count-by-place").DataTable({
            ajax: {
                url: "{{ route('total-count-by-place') }}",
                data: function(d) {
                    d.zone_name = $("#zone_name").val();
                    d.division_name = $("#division_name").val();
                    d.unit_name = $("#unit_name").val();
                    d.date = $("#date").val();
                    d.from_time = $("#from_time").val();
                    d.to_time = $("#to_time").val();
                    d.mode = $("#mode").val();
                    d.category = $("#category").val();
                    d.batch_type = $("#batch_type").val();
                }
            },
            columns: [
                dtIndexCol(),
                {
                    data: 'place_name',
                },
                {
                    data: 'total_count',
                },
            ]
        })

        function setFilter() {
            totalCountByPlace.draw();
        }
    </script>
@endpush
