@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Members'])
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-start ">
                    <h3 class="card-title font-weight-bold">
                        Members
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{ route('members.create') }}" class="btn btn-purple float-right"><i
                                    class="fas fa-plus mr-2"></i>Create</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table
                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline  collapsed"
                                    id="members-table">
                                    <thead>
                                        <tr>
                                            <th>ID </th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>User Number</th>
                                            <th>Unit Name</th>
                                            <th>Zone Name</th>
                                            <th>Division Name</th>
                                            <th>Date Of Birth</th>
                                            <th>Gender</th>
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
        $("#example_wrapper > .dt-buttons").appendTo("div.panel-heading");
        $(function() {
            $('#members-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('members.index') }}",
                columns: [{
                        data: 'id',
                        orderable: true
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                    },
                    {
                        data: 'user_number',
                        name: 'user_number',
                    },
                    {
                        data: 'unit_name',
                        name: 'unit_name',
                    },
                    {
                        data: 'zone_name',
                        name: 'unit_name'
                    },
                    {
                        data: 'division_name',
                        name: 'division_name'
                    },
                    {
                        data: 'dob',
                        name: 'dob',
                        orderable: false,
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'action',
                        orderable: false,
                    }
                ],
                "lengthMenu": [50, 100, 500, 1000, 2000, 5000, 10000, 20000],
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pageLength'
                    },
                    {
                        extend: 'csv',
                        filename: 'Members List'
                    },
                    {
                        extend: 'excel',
                        filename: 'Members List'
                    },
                    {
                        extend: 'pdf',
                        filename: 'Members List'
                    }
                ],
                "order": [
                    [0, "asc"]
                ]
            })
        })
    </script>
@endpush
