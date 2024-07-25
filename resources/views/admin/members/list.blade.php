@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Members'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Members
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (auth()->user()->id == 1 || auth()->user()->can('Create Members'))
                        <a href="{{ route('members.create') }}" class="btn btn-purple float-right"><i
                                class="fas fa-plus mr-2"></i>Create</a>
                    @endif

                    <button class="btn btn-purple float-right mr-2" type="button" data-toggle="collapse"
                        data-target="#regFilters" aria-expanded="false" aria-controls="regFilters">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="btn btn-purple float-right mr-2" onclick="clearFilters()"> <i class="fas fa-filter "></i>
                        Clear
                        Filters</button>
                </div>
                <div class="collapse container" id="regFilters">
                    <div class="card card-body shadow-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Register / Not reigister</label>
                                    <select class="form-control w-full" id="register_noregister"
                                        onchange="setFilter('register_noregister')">
                                        <option value="">-Select Filter-</option>
                                        <option value="registered">Registered</option>
                                        <option value="non-registered">Non Registered</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
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
        function clearFilters() {
            $('#register_noregister').val('').trigger('change');
            memberTable.draw();
        }

        $(function() {
            memberTable = $('#members-table').DataTable({
                ajax: {
                    url: "{{ route('members.index') }}",
                    data: function(d) {
                        d.register_noregister = $("#register_noregister").val();
                    }
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
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'user_number',
                        name: 'user_number'
                    },
                    {
                        data: 'unit_name',
                        name: 'unit_name'
                    },
                    {
                        data: 'zone_name',
                        name: 'zone_name'
                    },
                    {
                        data: 'division_name',
                        name: 'division_name'
                    },
                    {
                        data: 'dob',
                        name: 'dob',
                        orderable: false
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
            });
        });

        function setFilter() {
            memberTable.draw();
        }
    </script>
@endpush
