@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Common Data Report'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Common Data Report
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
                                    <label>Hotel Required</label>
                                    <select class="form-control w-full" id="hotel_required" onchange="setFilter()">
                                        <option value="">-Select Option-</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Need Attendant</label>
                                    <select class="form-control w-full" id="need_attendant" onchange="setFilter()">
                                        <option value="">-Select Option-</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cot/Bed</label>
                                    <select class="form-control w-full" id="cot_or_bed" onchange="setFilter()">
                                        <option value="">-Select Option-</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Health Concerns</label>
                                    <select class="form-control w-full" id="health_concern" onchange="setFilter()">
                                        <option value="">-Select Option-</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Management Experience</label>
                                    <select class="form-control w-full" id="management_experience" onchange="setFilter()">
                                        <option value="">-Select Option-</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="common-data-report-table">
                <th>SL.No </th>
                <th>Name Of Rukun</th>
                <th>Rukun ID</th>
                <th>Phone</th>
                <th>Unit</th>
                <th>Division</th>
                <th>Zone</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Hotel Required</th>
                <th>Need Attendent</th>
                <th>Bed/Cot</th>
                <th>Health Concern</th>
                <th>Management Experience</th>
                <th>Comments</th>
                <th>Year of Rukuniyat</th>
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
            $('#hotel_required').val('').trigger('change');
            $('#need_attendant').val('').trigger('change');
            $('#cot_or_bed').val('').trigger('change');
            $('#health_concern').val('').trigger('change');
            $('#management_experience').val('').trigger('change');
            setFilter();
        }
        $(function() {
            commonDataReportTable = $('#common-data-report-table').DataTable({
                ajax: {
                    url: "{{ route('common-data-report') }}",
                    data: function(d) {
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                        d.hotel_required = $('#hotel_required').val();
                        d.need_attendant = $('#need_attendant').val();
                        d.cot_or_bed = $('#cot_or_bed').val();
                        d.health_concern = $('#health_concern').val();
                        d.management_experience = $('#management_experience').val();
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
                        data: 'member.age',
                    },
                    {
                        data: 'hotel_required'
                    },
                    {
                        data: 'need_attendant'
                    },
                    {
                        data: 'cot_or_bed'
                    },
                    {
                        data: 'health_concern'
                    },
                    {
                        data: 'management_experience'
                    },
                    {
                        data: 'comments'
                    },
                    {
                        data: 'member.year_of_rukniyat',
                    },
                ],
            });
        })

        function setFilter() {
            commonDataReportTable.draw();
        }
    </script>
@endpush
