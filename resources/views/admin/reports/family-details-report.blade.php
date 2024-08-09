@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Family Details Report'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Family Details Report
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <button class="btn btn-purple float-right" type="button" data-toggle="collapse" data-target="#regFilters"
                        aria-expanded="false" aria-controls="regFilters">
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
                                        placeholder="Select Unit Name" onchange="setFilter()">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control w-full" id="gender" onchange="setFilter()">
                                        <option value="">-Select Gender-</option>
                                        <option value="female">Female</option>
                                        <option value="male">Male</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Age Group</label>
                                    <select class="form-control w-full" id="age_group" onchange="setFilter()">
                                        <option value="">-Select Type of Accompanying person-</option>
                                        <option value="mehram">Mehram</option>
                                        <option value="children">Children</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Interested In Volunteering</label>
                                    <select class="form-control w-full" id="interested_in_volunteering"
                                        onchange="setFilter()">
                                        <option value="">-Select Option-</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="null">Not Specified</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="family-details-report-table">
                <th>SL.No </th>
                <th>Family Member Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Type</th>
                <th>Name Of Rukun</th>
                <th>Rukun ID</th>
                <th>Phone</th>
                <th>Unit</th>
                <th>Division</th>
                <th>Zone</th>
                <th>Interested to volunteer</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        // clear filters
        function clearFilters() {
            $('#zone_name').val('').trigger('change');
            $('#division_name').val('').trigger('change');
            $('#unit_name').val('').trigger('change');
            $('#gender').val('').trigger('change');
            $('#age_group').val('').trigger('change');
            $('#interested_in_volunteering').val('').trigger('change');
            setFilter()
        }
        $(function() {
            familyDtlsTable = $('#family-details-report-table').DataTable({
                ajax: {
                    url: "{{ route('family-details-report') }}",
                    data: function(d) {
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                        d.gender = $("#gender").val()
                        d.age_group = $("#age_group").val()
                        d.interested_in_volunteering = $("#interested_in_volunteering").val()
                    }
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'name',
                        render: function(data, type, row, meta) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    {
                        data: 'gender',
                        render: function(data, type, row, meta) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    {
                        data: 'age',
                    },
                    {
                        data: 'type',
                        render: function(data, type, row, meta) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                    },
                    {
                        data: 'name_of_rukun',
                    },
                    {
                        data: 'rukun_id'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'unit_name'
                    },
                    {
                        data: 'division_name'
                    },
                    {
                        data: 'zone_name'
                    },
                    {
                        data: 'interested_in_volunteering'
                    }
                ],
            });
        })

        function setFilter() {
            familyDtlsTable.draw();
        }
    </script>
@endpush
