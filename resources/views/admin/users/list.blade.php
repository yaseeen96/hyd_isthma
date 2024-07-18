@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Users'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Users
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <a href="{{ route('user.create') }}" class="btn btn-purple float-right"><i
                         class="fas fa-plus mr-2"></i>Create</a>
                </div>
            </div>
            <x-table id="users-table">
                <th>SL.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">

        $(function() {
            usersTable = $('#users-table').DataTable({
                ajax: {
                    url: "{{ route('user.index') }}",
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: "role",
                        name: "role",
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
            });
        });
        $('table').on('click','.user-delete', function(e){
            $.ajax({
                url: $(this).data('href'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType: 'JSON',
                success: function(data) {
                    usersTable.draw();
                }
            })
        });
    </script>
@endpush
