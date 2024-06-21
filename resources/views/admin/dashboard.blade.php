@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Dashboard'])
@section('pagetitle', 'Dashboard')
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid  mt-3 px-3  rounded-2 ">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-purple"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">ARKANS</span>
                            <span class="info-box-number text-lg">{{ number_format($totArkansMems) }}</span>
                        </div>
                        <span class="info-box-icon"><a href="{{ route('members.index') }}" class="text-purple"><i
                                    class="fas fa-arrow-alt-circle-right"></i></a></span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-pen-fancy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">REGISTERED</span>
                            <span class="info-box-number text-lg">{{ number_format($totRegistered) }}</span>
                        </div>
                        <span class="info-box-icon"><a href="{{ route('registrations.index') }}?confirm_arrival="
                                class="text-success"><i class="fas fa-arrow-alt-circle-right"></i></a></span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">ATTENDEES</span>
                            <span class="info-box-number text-lg">{{ number_format($totAttendees) }}</span>
                        </div>
                        <span class="info-box-icon"><a href="{{ route('registrations.index') }}?confirm_arrival=1"
                                class="text-warning"><i class="fas fa-arrow-alt-circle-right"></i></a></span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-thumbs-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">NON ATTENDEES</span>
                            <span class="info-box-number text-lg">{{ number_format($totNonAttendees) }}</span>
                        </div>
                        <span class="info-box-icon"><a href="{{ route('registrations.index') }}?confirm_arrival=0"
                                class="text-danger"><i class="fas fa-arrow-alt-circle-right"></i></a></span>
                    </div>
                </div>
            </div>
            <div class="row  border-top  py-2">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Filter By</h5>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-purple float-right" onclick="clearFilters()"> <i class="fas fa-filter"></i> Clear
                        Filters</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label>DISTRICT NAME</label>
                        <select class="form-control select2bs4" style="width: 100%;" id="division_name"
                            onchange="getLocations('division_name', 'unit_name')">
                            <option value=""> -- select Division Name </option>
                            {{-- data will be dynamically filled --}}
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>UNIT NAME</label>
                        <select class="form-control select2bs4" style="width: 100%;" id="unit_name"
                            placeholder="Select Unit Name" onchange="updateChart()">
                            <option value=""> -- select Unit Name </option>
                            {{-- data will be dynamically filled --}}
                            {{-- @isset($locationsList['distnctUnitName'])
                                <option value="">All</option>
                                @foreach ($locationsList['distnctUnitName'] as $name)
                                    <option value="{{ $name->unit_name }}"> {{ $name->unit_name }}</option>
                                @endforeach
                            @endisset --}}
                        </select>
                    </div>
                </div>
            </div>
            <div class="row pt-2">
                <div class="col-md-6">
                    <div class="card card-widget widget-user p-3">
                        <h5 class="font-weight-bold">Registrations</h5>
                        <div class="d-flex align-items-center mb-4">
                            <div class="border rounded-circle"
                                style="background-color: rgb(75, 192, 192, 0.5); width: 20px;height: 20px;">
                            </div>
                            <span class="font-weight-bold ml-2">Registered: <span
                                    id="totRegistered">{{ number_format($totRegistered, 0) }}</span></span>
                            <div class="border rounded-circle ml-3"
                                style="background-color: rgb(255, 99, 132, 0.5); width: 20px;height: 20px;">
                            </div>
                            <span class="font-weight-bold ml-2">Not Registered: <span
                                    id="totNotRegistered">{{ number_format($totArkansMems - $totRegistered, 0) }}</span></span>
                        </div>
                        <canvas id="registrations"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-widget widget-user p-3">
                        <h5 class="font-weight-bold">Availibility</h5>
                        <div class="d-flex align-items-center mb-4">
                            <div class="border rounded-circle"
                                style="background-color: rgb(75, 192, 192, 0.5); width: 20px;height: 20px;">
                            </div>
                            <span class="font-weight-bold ml-2">Attendees: <span
                                    id="totAttendees">{{ number_format($totAttendees, 0) }}</span></span>
                            <div class="border rounded-circle ml-3"
                                style="background-color: rgb(255, 99, 132, 0.5); width: 20px;height: 20px;">
                            </div>
                            <span class="font-weight-bold ml-2">Non Attendees: <span
                                    id="totNonAttendees">{{ number_format($totNonAttendees, 0) }}</span></span>
                        </div>
                        <canvas id="attendees"></canvas>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@push('scripts')
    <script>
        // clear filters data
        function clearFilters() {
            $('#zone_name').val('').trigger('change');
            $('#division_name').val('').trigger('change');
            $('#unit_name').val('').trigger('change');
            updateChart();
        }
        // fill division and unit names dynamically 
        function getLocations(actionType, dataName) {
            const val = $(`#${actionType}`).val();
            $.ajax({
                url: dataName === "division_name" ? "{{ route('getDivisions') }}" : "{{ route('getUnits') }}",
                type: 'GET',
                data: {
                    [actionType]: val
                },
                success: function(data) {
                    let el = document.createElement('option');
                    el.value = '';
                    el.text = `-- Select ${dataName.replace('_', ' ')} --`;
                    if (data[dataName].length > 0) {
                        $(`#${dataName}`).empty().append(el);
                        data[dataName].forEach(function(item) {
                            let el = document.createElement('option');
                            el.value = item[dataName];
                            el.text = item[dataName];
                            $(`#${dataName}`).append(el);
                        });
                        if (actionType === "zone_name") {
                            $('#division_name').val('').trigger('change');
                            $('#unit_name').val('').trigger('change');
                        }
                        updateChart();
                    }
                }
            });

        }
        // chart data
        var registrationsCtx = document.getElementById('registrations').getContext('2d');
        var attendeesCtx = document.getElementById('attendees').getContext('2d');
        // doughnut chart for registrations
        var registrations = prepareChart(registrationsCtx, @json($filterData), 'registrations');
        // doughnut chart for attendees
        var attendees = prepareChart(attendeesCtx, @json($filterData), 'attendees');

        function updateChart() {
            const unit_name = $("#unit_name").val();
            const zone_name = $("#zone_name").val();
            const division_name = $("#division_name").val();
            $.ajax({
                url: "{{ route('dashboard') }}" +
                    `?unit_name=${unit_name}&zone_name=${zone_name}&division_name=${division_name}`,
                method: 'GET',
                success: function(data) {
                    // updating registrations chart data
                    registrations.data.labels = data.filterData.registrations.labels;
                    registrations.data.datasets[0].data = data.filterData.registrations.data;

                    // updating attendees chart data
                    attendees.data.labels = data.filterData.attendees.labels;
                    attendees.data.datasets[0].data = data.filterData.attendees.data;

                    $("#totRegistered").text(data.filterData.registrations.data[0]);
                    $("#totNotRegistered").text(data.filterData.registrations.data[1]);
                    $("#totAttendees").text(data.filterData.attendees.data[0]);
                    $("#totNonAttendees").text(data.filterData.attendees.data[1]);

                    registrations.update();
                    attendees.update();
                }
            });
        }

        function prepareChart(identifier, filterData, label) {
            var chart = new Chart(identifier, {
                type: 'bar',
                data: {
                    labels: filterData[label].labels,
                    datasets: [{
                        label: `${label}`,
                        data: filterData[label].data,
                        backgroundColor: [
                            'rgb(75, 192, 192, 0.2)',
                            'rgb(255, 99, 132, 0.2)',
                        ],
                        borderColor: [
                            'rgb(75, 192, 192)',
                            'rgb(255, 99, 132)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            return chart;
        }
    </script>
@endpush
