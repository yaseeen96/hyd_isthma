@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Global Report'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Global Report
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
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="global-report-table">
                <th>SL.No </th>
                <th>ZONE NAME</th>
                <th>Total Arkans</th>
                <th>Confirmed</th>
                <th>Non Attendees</th>
                <th>Registered</th>
                <th>Percentage Attendees</th>
                <th>Percentage Registered</th>
                <tfoot>
                    <tr class="bg-purple">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        function clearFilters() {
            $('#zone_name').val('').trigger('change');
            setFilter();
        }
        let totalRegistered;
        $(function() {
            globalReportTable = $('#global-report-table').DataTable({
                searching: false,
                ajax: {
                    url: "{{ route('global-report') }}",
                    data: function(d) {
                        d.zone_name = $("#zone_name").val()
                    },
                    dataSrc: function(json) {
                        totalRegistered = json.total_registered;
                        console.log(totalRegistered);
                        return json.data;
                    }
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'zone_name',
                    },
                    {
                        data: 'total_arkans',

                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (totalRegistered[row.zone_name]) {
                                return totalRegistered[row.zone_name].total_attendees;
                            }
                            return 0;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (totalRegistered[row.zone_name]) {
                                return totalRegistered[row.zone_name].total_non_attendees;
                            }
                            return 0;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (totalRegistered[row.zone_name]) {
                                return totalRegistered[row.zone_name].registered;
                            }
                            return 0;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (totalRegistered[row.zone_name]) {
                                return totalRegistered[row.zone_name].tot_attendees_percentage +
                                    '%';
                            }
                            return 0 + '%';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (totalRegistered[row.zone_name]) {
                                return totalRegistered[row.zone_name].tot_registered_percentage +
                                    '%';
                            }
                            return 0 + '%';
                        }
                    },
                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    // converting to interger to find total
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    var total_arkans = api.column(2).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(1).footer()).html('Total');
                    $(api.column(2).footer()).html(total_arkans);
                    $(api.column(3).footer()).html(totalRegistered['total_attendees']);
                    $(api.column(4).footer()).html(totalRegistered['total_non_attendees']);
                    $(api.column(5).footer()).html(totalRegistered['total_registered']);
                }
            });
        });

        function setFilter() {
            globalReportTable.draw();
        }
    </script>
@endpush
