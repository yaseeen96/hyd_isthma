@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Registrations'])
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-start ">
                    <h3 class="card-title font-weight-bold">
                        Registrations
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <button class="btn btn-purple float-right" type="button" data-toggle="collapse"
                                data-target="#regFilters" aria-expanded="false" aria-controls="regFilters">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button class="btn btn-purple float-right mr-2" onclick="clearFilters()"> <i
                                    class="fas fa-filter "></i> Clear
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
                                                {{-- data will be dynamically filled --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>UNIT NAME</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="unit_name"
                                                placeholder="Select Unit Name">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Confirm Arrival</label>
                                            <select class="form-control w-full" id="confirm_arrival"
                                                onchange="setFilter('confirm_arrival')">
                                                <option value="">-Select Filter-</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table
                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline collapsed"
                                    id="registrations-table">
                                    <thead class="">
                                        <tr>
                                            <th>ID </th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Unit Name</th>
                                            <th>Zone Name</th>
                                            <th>Division Name</th>
                                            <th>Confirm Arrival</th>
                                            <th>Non Availibility Reason</th>
                                            <th>Ammer Permission Taken</th>
                                            <th>Emergency Contact</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@push('scripts')
    <script type="text/javascript">
        // clear filters data
        function clearFilters() {
            $('#zone_name').val('').trigger('change');
            $('#division_name').val('').trigger('change');
            $('#unit_name').val('').trigger('change');
            updateChart();
        }
        $(function() {
            regTable = $('#registrations-table').DataTable({
                "responsive": false,
                "lengthChange": false,
                "autoWidth": true,
                processing: true,
                serverSide: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pageLength',
                    },
                    {
                        extend: 'csv',
                        filename: 'Registrations List'
                    },
                    {
                        extend: 'excel',
                        filename: 'Registrations List'
                    },
                    {
                        extend: 'pdf',
                        filename: 'Registrations List'
                    }
                ],
                ajax: {
                    url: "{{ route('registrations.index') }}",
                    data: function(d) {
                        d.confirm_arrival = $("#confirm_arrival").val()
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'member.name',
                    },
                    {
                        data: 'member.phone',
                    },
                    {
                        data: 'member.unit_name',
                    },
                    {
                        data: 'member.zone_name',
                    },
                    {
                        data: 'member.division_name',
                    },
                    {
                        data: 'confirm_arrival'
                    },
                    {
                        data: 'reason_for_not_coming'
                    },
                    {
                        data: 'ameer_permission_taken',
                    },
                    {
                        data: 'emergency_contact',
                    },
                    {
                        data: 'action'
                    }
                ],
                "lengthMenu": [50, 100, 500, 1000, 2000, 5000, 10000, 20000]
            });

        });

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
                        console.log(data[dataName].length);
                        $(`#${dataName}`).empty().append(el);
                        data[dataName].forEach(function(item) {
                            let el = document.createElement('option');
                            el.value = item[dataName];
                            el.text = item[dataName];
                            $(`#${dataName}`).append(el);
                        });
                    }
                }
            });
            setFilter();
        }

        function setFilter(type) {
            regTable.draw();
        }
    </script>
@endpush
