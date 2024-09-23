@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'CheckInOut Entired'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            CheckInOut Entires
        </x-slot>
        <div class="card-body">
            <div class="row">
                {{-- Filters & Create Entries Section --}}
            </div>
            <x-table id="checkinout-entires">
                <th>SL.No </th>
                <th>Type</th>
                <th>Rukun ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Unit</th>
                <th>Halqa</th>
                <th>Company Name</th>
                <th>Department Name</th>
                <th>Govt Origanization</th>
                <th>Place Name</th>
                <th>Date&Time</th>
                <th>Mode</th>
                <th>Scanned By</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        function clearFilters() {
            setFilter();
        }
        const checkInOutEntires = $("#checkinout-entires").DataTable({
            ajax: {
                url: "{{ route('checkInOutEntries.index') }}",
                data: function(d) {}
            },
            columns: [
                dtIndexCol(),
                {
                    data: 'type',
                },
                {
                    data: 'rukun_id',
                },
                {
                    data: 'name',
                },
                {
                    data: 'age',
                },
                {
                    data: 'gender'
                },
                {
                    data: 'unit'
                },
                {
                    data: 'halqa'
                },
                {
                    data: 'company_name'
                },
                {
                    data: 'department_name'
                },
                {
                    data: 'govt_organization'
                },
                {
                    data: 'place'
                },
                {
                    data: 'datetime'
                },
                {
                    data: 'mode'
                },
                {
                    data: 'user'
                },
            ]
        })

        function setFilter() {
            programsTable.draw();
        }
    </script>
@endpush
