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
                                    <label>Date & Time</label>
                                    <input type="date" class="form-control" id="date_time" onchange="setFilter()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Travel Mode</label>
                                    <select class="form-control w-full" id="travel_mode" onchange="setFilter()">
                                        <option value="">-Select Option-</option>
                                        <option value="bus">Bus</option>
                                        <option value="train">Train</option>
                                        <option value="plane">Plane</option>
                                        <option value="car">Car</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Station Name</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="end_point"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        @if (count(config('stationslist')) > 0)
                                            @foreach (config('stationslist') as $station)
                                                <option value="{{ $station }}"> {{ $station }}</option>
                                            @endforeach
                                        @endif
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
            $('#date_time').val(null);
            $('#travel_mode').val('').trigger('change');
            $('#end_point').val('');
            $('#mode_identifier').val('');
            setFilter();
        }
        // $('#end_point').on('keyup', function() {
        //     const value = $(this).val();
        //     if (value.length >= 3) {
        //         setFilter();
        //     }
        // })
        $(function() {
            arrivalReportTable = $('#arrival-report-table').DataTable({
                ajax: {
                    url: "{{ route('arrival-report') }}",
                    data: function(d) {
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                        d.date_time = $("#date_time").val()
                        d.travel_mode = $('#travel_mode').val()
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
