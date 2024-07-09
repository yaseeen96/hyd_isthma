@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Health Report'])
@section('content')
<section class="content">
    <div class="container-fluid mt-3 px-3 rounded-2">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-start">
                <h3 class="card-title font-weight-bold">Health Report</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-5">
                        <button class="btn btn-purple float-right" type="button" data-toggle="collapse"
                            data-target="#healthFilters" aria-expanded="false" aria-controls="healthFilters">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="btn btn-purple float-right mr-2" onclick="clearFilters()"> <i
                                class="fas fa-filter "></i> Clear Filters</button>
                    </div>
                    <div class="collapse container" id="healthFilters">
                        <div class="card card-body shadow-none">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ZONE NAME</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="zone_name"
                                            onchange="setFilter()">
                                            @isset($locationsList['distnctZoneName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctZoneName'] as $name)
                                            <option value="{{ $name->zone_name }}"> {{ $name->zone_name }}</option>
                                            @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>GENDER</label>
                                        <select class="form-control select2bs4" style="width: 100%;" id="gender"
                                            onchange="setFilter()">
                                            <option value="">All</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
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
                                id="health-report-table">
                                <thead>
                                    <tr>
                                        <th>Sl No</th>
                                        <th>Zone Name</th>
                                        <th>Gender</th>
                                        <th>Health Concern</th>
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
@endsection

@push('scripts')
<script type="text/javascript">
function clearFilters() {
    $('#zone_name').val('').trigger('change');
    $('#gender').val('').trigger('change');
    healthTable.draw();
}

$(function() {
    healthTable = $('#health-report-table').DataTable({
        "responsive": false,
        "lengthChange": false,
        "autoWidth": true,
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        buttons: [{
                extend: 'pageLength'
            },
            {
                extend: 'csv',
                filename: 'Health Report',
                extraOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                filename: 'Health Report',
                extraOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdf',
                filename: 'Health Report',
                extraOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ],
        ajax: {
            url: "{{ route('health-report') }}",
            data: function(d) {
                d.zone_name = $("#zone_name").val();
                d.gender = $("#gender").val();
            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'zone_name'
            },
            {
                data: 'gender'
            },
            {
                data: 'health_concern'
            },
            {
                data: 'action'
            }
        ],
        "lengthMenu": [50, 100, 500, 1000, 2000, 5000, 10000, 20000]
    });
});

function setFilter() {
    healthTable.draw();
}
</script>
@endpush