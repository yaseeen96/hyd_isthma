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
                <th>Batch Type</th>
                <th>Batch ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Zone</th>
                <th>Division </th>
                <th>Unit</th>
                <th>Place</th>
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
                    data: 'batch_type',
                },
                {
                    data: 'batch_id',
                },
                {
                    data: 'name'
                },
                {
                    data: 'gender',
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
                    data: 'place'
                },
                {
                    data: 'datetime',
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
