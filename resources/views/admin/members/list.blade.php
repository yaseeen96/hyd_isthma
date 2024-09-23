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
                                        <option value="">All</option>
                                        {{-- data will be dynamically filled --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>UNIT NAME</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="unit_name"
                                        placeholder="Select Unit Name" onchange="setFilter('unit_name')">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
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
                <th>Age</th>
                <th>Registration Status</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        function clearFilters() {
            $('#zone_name').val('').trigger('change');
            $('#division_name').val('').trigger('change');
            $('#unit_name').val('').trigger('change');
            $('#register_noregister').val('').trigger('change');
            setFilter();
        }
        $(function() {
            memberTable = $('#members-table').DataTable({
                ajax: {
                    url: "{{ route('members.index') }}",
                    data: function(d) {
                        d.register_noregister = $("#register_noregister").val();
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                    }
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'name',
                    },
                    {
                        data: 'email',
                    },
                    {
                        data: 'phone',
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
                        orderable: false
                    },
                    {
                        data: 'gender',
                    },
                    {
                        data: 'age',
                    },
                    {
                        data: 'reg_status',
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
