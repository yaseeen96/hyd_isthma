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

                        </div>
                        <div class="collapse container" id="regFilters">
                            <div class="card card-body shadow-none">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>UNIT NAME</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="unit_name"
                                                onchange="setFilter('unit_name')" placeholder="Select Unit Name">
                                                @isset($locationsList['distnctUnitName'])
                                                    <option value="">All</option>
                                                    @foreach ($locationsList['distnctUnitName'] as $name)
                                                        <option value="{{ $name->unit_name }}"> {{ $name->unit_name }}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ZONE NAME</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="zone_name"
                                                onchange="setFilter('zone_name')">
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
                                            <label>DIVISION NAME</label>
                                            <select class="form-control select2bs4" style="width: 100%;" id="division_name"
                                                onchange="setFilter('division_name')">
                                                @isset($locationsList['distnctDivisionName'])
                                                    <option value="">All</option>
                                                    @foreach ($locationsList['distnctDivisionName'] as $name)
                                                        <option value="{{ $name->division_name }}"> {{ $name->division_name }}
                                                        </option>
                                                    @endforeach
                                                @endisset
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
            $('#unit_name').on('change', function() {
                $.ajax({
                    url: "{{ env('APP_URL') . '/api/v1/getZones' }}",
                    type: 'GET',
                    data: {
                        unit_name: $(this).val()
                    },
                    success: function(data) {
                        if (data.zone_name != null) {
                            $('#zone_name').val(data.zone_name).trigger('change');
                        }
                    }
                });
            });
        });

        function setFilter(type) {
            regTable.draw();
        }
    </script>
@endpush
