@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Members'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Members
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <a href="{{ route('members.create') }}" class="btn btn-purple float-right"><i
                            class="fas fa-plus mr-2"></i>Create</a>
                </div>
            </div>
            <x-table id="members-table">
                <th>SL.No </th>
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
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('#members-table').DataTable({
                ajax: "{{ route('members.index') }}",
                columns: [
                    dtIndexCol(),
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
            })
        })
    </script>
@endpush
