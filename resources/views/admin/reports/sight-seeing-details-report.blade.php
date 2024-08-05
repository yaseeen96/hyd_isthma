@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Sight Seeing Details Report'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Sight Seeing Details Report
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
                                        <option value="">-Select Option-</option>
                                        <option value="female">Female</option>
                                        <option value="male">Male</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="sight-seeing-report-table">
                <th>SL.No </th>
                <th>Name Of Rukun</th>
                <th>Rukun ID</th>
                <th>Phone</th>
                <th>Unit</th>
                <th>Division</th>
                <th>Zone</th>
                <th>Gender</th>
                <th>Number of Members</th>
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
            setFilter();
        }
        $(function() {
            sightSeeingDtlsReport = $('#sight-seeing-report-table').DataTable({
                ajax: {
                    url: "{{ route('sight-seeing-details-report') }}",
                    data: function(d) {
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                        d.gender = $("#gender").val()
                    }
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'member.name',
                    },
                    {
                        data: 'member.user_number',
                    },
                    {
                        data: 'member.phone',
                    },
                    {
                        data: 'member.unit_name',
                    },
                    {
                        data: 'member.division_name',
                    },
                    {
                        data: 'member.zone_name',
                    },
                    {
                        data: 'member.gender',
                    },
                    {
                        data: 'total_members'
                    },
                ],
            });
        })

        function setFilter() {
            sightSeeingDtlsReport.draw();
        }
    </script>
@endpush
