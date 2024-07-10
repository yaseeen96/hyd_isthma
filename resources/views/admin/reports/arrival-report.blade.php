@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Arrival Report'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Arrival Report
        </x-slot>
        <div class="card-body">
            <x-table id="arrival-report-table">
                <th>SL.No </th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Unit Name</th>
                <th>Zone Name</th>
                <th>Division Name</th>
                <th>Travel Mode</th>
                <th>Departure</th>
                <th>Start Point</th>
                <th>End Point</th>
                <th>Mode Identifier</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            arrivalReportTable = $('#arrival-report-table').DataTable({
                ajax: {
                    url: "{{ route('arrival-report') }}",
                    data: function(d) {
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
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
                        data: 'travel_mode'
                    },
                    {
                        data: 'date_time'
                    },
                    {
                        data: 'start_point'
                    },
                    {
                        data: 'end_point'
                    },
                    {
                        data: 'mode_identifier'
                    }
                ],
            });
        })
    </script>
@endpush
