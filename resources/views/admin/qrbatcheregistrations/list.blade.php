@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'QR Batch Registraitons'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            QR Batch Registraitons
        </x-slot>
        <div class="card-body">
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
                    </div>
                </div>
            </div>
            <x-table id="qrbatchregistrations-entires">
                <th>SL.No </th>
                <th>Batch ID</th>
                <th>Batch Type</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Zone Name</th>
                <th>Division Name</th>
                <th>Unit Name</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        function clearFilters() {
            $("#zone_name").val('').trigger('change');
            $("#division_name").val('').trigger('change');
            $("#unit_name").val('').trigger('change');
            setFilter();
        }
        const qrBatchRegistrations = $("#qrbatchregistrations-entires").DataTable({
            ajax: {
                url: "{{ route('qrBatchRegistrations.index') }}",
                data: function(d) {
                    d.zone_name = $("#zone_name").val();
                    d.division_name = $("#division_name").val();
                    d.unit_name = $("#unit_name").val();
                }
            },
            columns: [
                dtIndexCol(),
                {
                    data: 'batch_id',
                },
                {
                    data: 'batch_type',
                },
                {
                    data: 'full_name',
                },
                {
                    data: 'gender',
                },
                {
                    data: 'email'
                },
                {
                    data: 'phone_number'
                },
                {
                    data: 'zone_name'
                },
                {
                    data: 'division_name'
                },
                {
                    data: 'unit_name'
                },
                {
                    data: 'action',
                    orderable: false
                }
            ]
        })

        function setFilter() {
            qrBatchRegistrations.draw();
        }
    </script>
@endpush
