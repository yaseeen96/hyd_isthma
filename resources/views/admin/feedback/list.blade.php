@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Feedback'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Feedbacks
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
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
                                    <label>Program</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="program_id"
                                        placeholder="Select Program Name" onchange="setFilter('program_id')">
                                        <option value="">All</option>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}">{{ $program->topic }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Feedback Type</label>
                        <select class="form-control select2bs4" id="feedback_type" onchange="setFilter()">
                            <option value="event">Event</option>
                            <option value="program">Program</option>
                        </select>
                    </div>
                </div>
            </div>
            <x-table id="feedback-table">
                <th>SL.No </th>
                <th>Feedback Type</th>
                <th>Name Of Rukun</th>
                <th>Rukun ID</th>
                <th>Phone</th>
                <th>Unit</th>
                <th>Division</th>
                <th>Zone</th>
                <th>Gender</th>
                <th>Title</th>
                <th>Description</th>
                <th>DateTime</th>
                <th>Program Name</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        function clearFilters() {
            $('#feedback_type').val('').trigger('change');
            $('#zone_name').val('').trigger('change');
            $('#division_name').val('').trigger('change');
            $('#unit_name').val('').trigger('change');
            $('#program_id').val('').trigger('change');

            setFilter();
        }
        $(function() {
            feebackTable = $('#feedback-table').DataTable({
                ajax: {
                    url: "{{ route('feedback.index') }}",
                    data: function(d) {
                        d.feedback_type = $('#feedback_type').val();
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                        d.program_id = $("#program_id").val()

                    }
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'feedback_type',
                    },
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
                        render: function(data, type, row, meta) {
                            return data ? data.charAt(0).toUpperCase() + data.slice(1) : '';
                        }
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'datatime'
                    },
                    {
                        data: 'program_name'
                    },
                ],
            });
        });

        function setFilter() {
            feebackTable.draw();
        }
    </script>
@endpush
