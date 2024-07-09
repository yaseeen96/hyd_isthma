@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Travel Report'])
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid  mt-3 px-3  rounded-2 ">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-start ">
                    <h3 class="card-title font-weight-bold">
                        Travel Report
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table
                                    class="custom-table-head nowrap table table-bordered table-hover dataTable dtr-inline  collapsed"
                                    id="travel-report-table">
                                    <thead>
                                        <tr>
                                            <th>SL.No </th>
                                            <th>Travel Mode</th>
                                            <th>Date & Time</th>
                                            <th>Start Point</th>
                                            <th>End Point</th>
                                            <th>Mode Identifier</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@push('scripts')
    <script type="text/javascript"></script>
@endpush
