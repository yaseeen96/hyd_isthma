@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Arrival Report'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Arrival Report
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
                                    <label>From Date & Time</label>
                                    <input type="text" class="form-control datetimepicker-input datetime" id="from_date"
                                        data-toggle="datetimepicker" data-target="#date_time" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>To Date & Time</label>
                                    <input type="text" class="form-control datetimepicker-input datetime" id="to_date"
                                        data-toggle="datetimepicker" data-target="#timepicker" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Travel Mode</label>
                                    <select class="form-control w-full" id="travel_mode">
                                        <option value="">All</option>
                                        @foreach (config('stationslist') as $key => $value)
                                            <option value="{{ $key }}">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Station Name</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="end_point"
                                        onchange="setFilter()">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bus / Train Number</label>
                                    <input type="text" class="form-control w-full" id="mode_identifier"
                                        onchange="setFilter()">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="arrival-report-table">
                <th>SL.No </th>
                <th>Name Of Rukun</th>
                <th>Rukun ID</th>
                <th>Phone</th>
                <th>Unit</th>
                <th>Division</th>
                <th>Zone</th>
                <th>Gender</th>
                <th>Age</th>
                <th>No. Family Members Accompanying</th>
                <th>Travel Mode</th>
                <th>Date & TIme </th>
                <th>Station Name</th>
                <th>Bus/Train Number</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        // clear filters
        function clearFilters() {
            $('#zone_name').val('').trigger('change');
            $('#division_name').val('').trigger('change');
            $('#unit_name').val('').trigger('change');
            $('#from_date').val(null);
            $('#to_date').val(null);
            $('#travel_mode').val('').trigger('change');
            $('#end_point').val('').trigger('change');
            $('#mode_identifier').val('');
            setFilter();
        }
        $('.datetime').on('change.datetimepicker', function() {
            setFilter();
        })
        $('#travel_mode').on('change', function() {
            $('#end_point').empty();
            $.ajax({
                url: "{{ route('get-station-names') }}",
                type: 'GET',
                data: {
                    travel_mode: $('#travel_mode').val()
                },
                success: function(data) {
                    let el = document.createElement('option');
                    el.text = 'All';
                    el.value = '';
                    $('#end_point').append(el);
                    if (data.station_names.length > 0) {
                        data.station_names.forEach(function(statation) {
                            let el = document.createElement('option');
                            el.text = statation;
                            el.value = statation;
                            $('#end_point').append(el);
                        });
                    }
                }
            });
            setFilter();
        });
        $(function() {
            arrivalReportTable = $('#arrival-report-table').DataTable({
                ajax: {
                    url: "{{ route('arrival-report') }}",
                    data: function(d) {
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                        d.travel_mode = $('#travel_mode').val()
                        d.from_date = $('#from_date').val()
                        d.to_date = $('#to_date').val()
                        d.end_point = $('#end_point').val()
                        d.mode_identifier = $('#mode_identifier').val()
                    }
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'member.name',
                    },
                    {
                        data: 'member.user_number',
                    },
                    {
                        data: 'member.phone',
                    },
                    {
                        data: 'member.unit_name',
                    },
                    {
                        data: 'member.division_name',
                    },
                    {
                        data: 'member.zone_name',
                    },
                    {
                        data: 'member.gender',
                    },
                    {
                        data: 'member.age',
                    },
                    {
                        data: 'total_family_members'
                    },
                    {
                        data: 'travel_mode'
                    },
                    {
                        data: 'date_time'
                    },
                    {
                        data: 'end_point'
                    },
                    {
                        data: 'mode_identifier'
                    }
                ],
            });
        })

        function setFilter() {
            arrivalReportTable.draw();
        }
    </script>
@endpush
