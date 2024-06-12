@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Advertisement'])
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-start ">
                    <h3 class="card-title font-weight-bold">
                        Members
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table custom-table-head table-separate table-borderless" id="members-table">
                                <thead>
                                    <tr>
                                        <th>ID </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>User Number</th>
                                    </tr>
                                </thead>
                            </table>
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
            console.log("1");
        });

        $(function() {
            $('#members-table').DataTable({
                processing: true,
                serverSide: true,
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
                    }
                ]
            })
        })
        $('table').on('click', '.advertisement-delete', function(e) {
            var href = $(this).data('href');
            $('.btn_delete_advertisement').on('click', function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    datatype: 'JSON',
                    url: href,
                    success: function(response) {
                        $('#delete_advertisement').modal('hide');
                        $('members-table').DataTable().ajax.reload();
                        toastr.success("Banner deleted successfully", "Success");
                    }
                });
            });
        });
    </script>
@endpush
