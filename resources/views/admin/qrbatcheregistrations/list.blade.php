@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'QR Batch Registraitons'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            QR Batch Registraitons
        </x-slot>
        <div class="card-body">
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
            setFilter();
        }
        const qrBatchRegistrations = $("#qrbatchregistrations-entires").DataTable({
            ajax: {
                url: "{{ route('qrBatchRegistrations.index') }}",
                data: function(d) {}
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
