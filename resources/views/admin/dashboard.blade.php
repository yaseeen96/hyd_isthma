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
                        <span class="info-box-icon"><a href="{{ route('registrations.index') }}" class="text-success"><i
                                    class="fas fa-arrow-alt-circle-right"></i></a></span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-check"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">ATTENDEES</span>
                            <span class="info-box-number text-lg">{{ number_format($totAttendees) }}</span>
                        </div>
                        <span class="info-box-icon"><a href="{{ route('registrations.index') }}" class="text-warning"><i
                                    class="fas fa-arrow-alt-circle-right"></i></a></span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-thumbs-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-weight-bold">NON ATTENDEES</span>
                            <span class="info-box-number text-lg">{{ number_format($totNonAttendees) }}</span>
                        </div>
                        <span class="info-box-icon"><a href="{{ route('registrations.index') }}" class="text-danger"><i
                                    class="fas fa-arrow-alt-circle-right"></i></a></span>
                    </div>
                </div>
            </div>
            <div class="row  border-top  py-2">
                <div class="col-md-6">
                    <h5 class="font-weight-bold">Filter By</h5>
                </div>
                <div class="col-md-6">
                    {{-- <button class="btn btn-purple float-right"> <i class="fas fa-filter"></i> Clear Filters</button> --}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>UNIT NAME</label>
                        <select class="form-control select2bs4" style="width: 100%;" id="unit_name" onchange="updateChart()"
                            placeholder="Select Unit Name">
                            @isset($distnctUnitName)
                                <option value="">All</option>
                                @foreach ($distnctUnitName as $name)
                                    <option value="{{ $name->unit_name }}"> {{ $name->unit_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>ZONE NAME</label>
                        <select class="form-control select2bs4" style="width: 100%;" id="zone_name"
                            onchange="updateChart()">
                            @isset($distnctZoneName)
                                <option value="">All</option>
                                @foreach ($distnctZoneName as $name)
                                    <option value="{{ $name->zone_name }}"> {{ $name->zone_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>DIVISION NAME</label>
                        <select class="form-control select2bs4" style="width: 100%;" id="division_name"
                            onchange="updateChart()">
                            @isset($distnctDivisionName)
                                <option value="">All</option>
                                @foreach ($distnctDivisionName as $name)
                                    <option value="{{ $name->division_name }}"> {{ $name->division_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>
            </div>
            <div class="row pt-2">
                <div class="col-md-6">
                    <div class="card card-widget widget-user p-3">
                        <h5 class="font-weight-bold">Registrations</h5>
                        <canvas id="registrations"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-widget widget-user p-3">
                        <h5 class="font-weight-bold">Availibility</h5>
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
                    console.log(data);
                    // updating registrations chart data
                    registrations.data.labels = data.filterData.registrations.labels;
                    registrations.data.datasets[0].data = data.filterData.registrations.data;
                    // updating attendees chart data
                    attendees.data.labels = data.filterData.attendees.labels;
                    attendees.data.datasets[0].data = data.filterData.attendees.data;
                    registrations.update();
                }
            });
        }

        function prepareChart(identifier, filterData, label) {
            console.log(filterData)
            var chart = new Chart(identifier, {
                type: 'doughnut',
                data: {
                    // labels: filterData.[label].labels, // hwo do I pass dynamic js varibale here
                    labels: filterData[label].labels,
                    datasets: [{
                        label: 'Total Attendees',
                        data: filterData[label].data,
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                        ],
                        hoverOffset: 4
                    }]
                },
            });
            return chart;
        }
    </script>
@endpush
