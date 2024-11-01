@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Qr Operators'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Qr Operators
        </x-slot>
        <div class="card-body">
            @php
                $message = Session::get('success') ?? Session::get('error');
            @endphp
            @if ($message)
                <div class="alert {{ Session::get('success') ? 'alert-success' : 'alert-danger' }} alert-block">
                    <button type="button" class="close text-white" data-dismiss="alert">X</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    @if (auth()->user()->id == 1 || auth()->user()->can('Create QrOperators'))
                        <a href="{{ route('qrOperators.create') }}" class="btn btn-purple float-right my-1"><i
                                class="fas fa-plus mr-2"></i>Add
                            Qr Operator</a>
                    @endif
                    <button class="btn btn-purple float-right mr-2 my-1" type="button" data-toggle="collapse"
                        data-target="#bulkUpload" aria-expanded="false" aria-controls="bulkUpload">
                        <i class="fas fa-cloud-download-alt"></i> Bulk Upload

                    </button>
                </div>
                <div class="collapse container {{ ($errors->has('qroperators_bulkupload') && $errors->first('qroperators_bulkupload')) || Session::get('error') ? 'show' : '' }}"
                    id="bulkUpload">
                    <div class="card card-body shadow-none">
                        <div class="row">
                            <div class="col-lg-3">
                                <span class="btn btn-purple"><a class="text-white"
                                        href="{{ asset('assets/documents/volunteers_sample_upload_file.csv') }}">Download
                                        the sample file</a></span>
                            </div>
                            <div class="col-lg-6">
                                {{-- file upload form --}}
                                <form action="{{ route('qrOperators.bulkUpload') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" type="file" class="custom-file-input"
                                                id="qroperators_bulkupload" name="qroperators_bulkupload">
                                            <label class="custom-file-label" for="qroperators_bulkupload">Choose
                                                file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button type="submit" class="input-group-text">Upload</button>
                                        </div>
                                    </div>
                                    @if ($errors->has('qroperators_bulkupload'))
                                        <span class="text-danger">
                                            {{ $errors->first('qroperators_bulkupload') }}
                                        </span>
                                    @endif
                                </form>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <x-table id="qroperators-table">
                <th>SL.No</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            qrOperatorsTable = $('#qroperators-table').DataTable({
                ajax: {
                    url: "{{ route('qrOperators.index') }}",
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'name',
                    },
                    {
                        data: "phone_number",
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
            });
        });
        $('table').on('click', '.user-delete', function(e) {
            $.ajax({
                url: $(this).data('href'),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(data) {
                    qrOperatorsTable.draw();
                }
            })
        });
    </script>
@endpush
