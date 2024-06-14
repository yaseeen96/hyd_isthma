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
                            <div class="table-responsive">
                                <table
                                    class="custom-table-head  table table-bordered table-hover dataTable dtr-inline  collapsed"
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
                                            <th>DOB</th>
                                            <th>Gender</th>
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
            $('#members-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                dom: 'Bfrtip',
                buttons: [{
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
                ajax: "{{ route('members.index') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'user_number',
                    },
                    {
                        data: 'unit_name',
                    },
                    {
                        data: 'zone_name',
                    },
                    {
                        data: 'division_name',
                    },
                    {
                        data: 'dob',
                    },
                    {
                        data: 'gender',
                    }
                ]
            })
        })
    </script>
@endpush
