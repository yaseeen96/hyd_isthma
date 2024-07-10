@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Health Report'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Health Report
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 mb-5">
                    <button class="btn btn-purple float-right" type="button" data-toggle="collapse"
                        data-target="#healthFilters" aria-expanded="false" aria-controls="healthFilters">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="btn btn-purple float-right mr-2" onclick="clearFilters()"> <i class="fas fa-filter "></i>
                        Clear Filters</button>
                </div>
                <div class="collapse container" id="healthFilters">
                    <div class="card card-body shadow-none">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>ZONE NAME</label>
                                    <select class="form-control select2bs4" style="width: 100%;" id="zone_name"
                                        onchange="setFilter()">
                                        @isset($locationsList['distnctZoneName'])
                                            <option value="">All</option>
                                            @foreach ($locationsList['distnctZoneName'] as $name)
                                                <option value="{{ $name->zone_name }}"> {{ $name->zone_name }}</option>
                                            @endforeach
                                        @endisset
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
            <x-table id="health-report-table">
                <th>Sl No</th>
                <th>Zone Name</th>
                <th>Gender</th>
                <th>Health Concern</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection

@push('scripts')
    <script type="text/javascript">
        function clearFilters() {
            $('#zone_name').val('').trigger('change');
            $('#gender').val('').trigger('change');
            healthTable.draw();
        }
        $(function() {
            healthTable = $('#health-report-table').DataTable({
                ajax: {
                    url: "{{ route('health-report') }}",
                    data: function(d) {
                        d.zone_name = $("#zone_name").val();
                        d.gender = $("#gender").val();
                    }
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'zone_name'
                    },
                    {
                        data: 'gender'
                    },
                    {
                        data: 'health_concern'
                    },
                    {
                        data: 'action'
                    }
                ],
            });
        });

        function setFilter() {
            healthTable.draw();
        }
    </script>
@endpush
