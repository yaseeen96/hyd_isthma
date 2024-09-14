@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Payment Details Report'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Payment Details Report
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
                                    <label>PAID STATUS</label>
                                    <select class="form-control w-full" id="paid_status" onchange="setFilter()">
                                        <option value="">-Select Paid Status-</option>
                                        <option value="yes">Paid</option>
                                        <option value="no">Un Paid</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="payment-details-report-table">
                <th>SL.No </th>
                <th>Name Of Rukun</th>
                <th>Rukun ID</th>
                <th>Phone</th>
                <th>Unit</th>
                <th>Division</th>
                <th>Zone</th>
                <th>Gender</th>
                <th>Total Fees</th>
                <th>Fees Paid</th>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="bg-success"></th>
                        <th class="bg-secondary"></th>
                    </tr>
                </tfoot>
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
            $('#paid_status').val('').trigger('change');
            setFilter();
        }
        $(function() {
            paymentDtlsTable = $('#payment-details-report-table').DataTable({
                ajax: {
                    url: "{{ route('payment-details-report') }}",
                    data: function(d) {
                        d.unit_name = $("#unit_name").val()
                        d.zone_name = $("#zone_name").val()
                        d.division_name = $("#division_name").val()
                        d.paid_status = $("#paid_status").val()
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
                        data: 'member.unit_name'
                    },
                    {
                        data: 'member.division_name'
                    },
                    {
                        data: 'member.zone_name'
                    },
                    {
                        data: 'member.gender'
                    },
                    {
                        data: 'member_fees'
                    },
                    {
                        data: 'fees_paid_to_ameer'
                    }
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
                    var total_fees = api.column(8, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var total_fees_paid = api.column(9, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    $(api.column(7).footer()).html('Total');
                    $(api.column(8).footer()).html(total_fees);
                    $(api.column(9).footer()).html(total_fees_paid);
                }
            });
        })

        function setFilter() {
            paymentDtlsTable.draw();
        }
    </script>
@endpush