@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Tour Report'])
@section('content')
<x-content-wrapper>
    <x-slot:title>
        Tour Report
        </x-slot>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <button class="btn btn-purple float-right" type="button" data-toggle="collapse"
                        data-target="#healthFilters" aria-expanded="false" aria-controls="healthFilters">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="btn btn-purple float-right mr-2" onclick="clearFilters()"> <i
                            class="fas fa-filter "></i>
                        Clear Filters</button>
                </div>
                <div class="collapse container" id="healthFilters">
                    <div class="card card-body shadow-none">
                        <div class="row">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>DISTRICT NAME</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="division_name"
                                        onchange="getLocations('division_name', 'unit_name')">
                                        <option value=""> -- select Division Name </option>
                                        {{-- data will be dynamically filled --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>UNIT NAME</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="unit_name"
                                        placeholder="Select Unit Name" onchange="updateChart()">
                                        <option value=""> -- select Unit Name </option>
                                        {{-- data will be dynamically filled --}}
                                        {{-- @isset($locationsList['distnctUnitName'])
                                <option value="">All</option>
                                @foreach ($locationsList['distnctUnitName'] as $name)
                                    <option value="{{ $name->unit_name }}"> {{ $name->unit_name }}</option>
                                        @endforeach
                                        @endisset --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>GENDER</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="gender"
                                        onchange="setFilter()">
                                        <option value="">All</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="tour-report-table">
                <th>SL.No </th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Unit Name</th>
                <th>Zone Name</th>
                <th>Division Name</th>
                <th>Gender</th>
                <th> Number of People opted.</th>
            </x-table>
        </div>
</x-content-wrapper>
@endsection

@push('scripts')
<script type="text/javascript">

$(function() {
    healthTable = $('#tour-report-table').DataTable({
        ajax: {
            url: "{{ route('tour-report') }}",
            data: function(d) {
                d.gender = $("#gender").val();
            },
            dataSrc: function(json) {
                console.log("Response Data:", json.data);
                return json.data;
            }
        },
        columns: [
            dtIndexCol(),
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
                data: 'member.gender'
            },
            {
                data: 'members_count',
            },

        ],
    });
});

function setFilter() {
    healthTable.draw();
}
</script>
@endpush