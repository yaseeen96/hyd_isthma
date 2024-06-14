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
                            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4"></div>
                        </div>
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table
                                    class="custom-table-head  table table-bordered table-hover dataTable dtr-inline collapsed"
                                    id="registrations-table">
                                    <thead>
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
            $('#registrations-table').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": true,
                processing: true,
                serverSide: true,
                // "paging": false,
                dom: 'Bfrtip',
                buttons: [{
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
                ajax: "{{ route('registrations.index') }}",
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
            });
        })
    </script>
@endpush
