@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Session Themes'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Theme Sessions
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (auth()->user()->id == 1 || auth()->user()->can('Create SessionThemes'))
                        <a href="{{ route('sessiontheme.create') }}" class="btn btn-purple float-right"><i
                                class="fas fa-plus mr-2"></i>Add
                            Seesion Theme</a>
                    @endif
                </div>
            </div>
            <x-table id="sessiontheme-table">
                <th>SL.No</th>
                <th>Session Name</th>
                <th>Type</th>
                <th>Date</th>
                <th>From-To Time</th>
                <th>Status</th>
                <th>Hall Name</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(function() {
            sessionthemeTable = $('#sessiontheme-table').DataTable({
                ajax: {
                    url: "{{ route('sessiontheme.index') }}",
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'theme_name',
                    },
                    {
                        data: 'theme_type',
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'from_to_time'
                    },
                    {
                        data: "status",
                    },
                    {
                        data: 'hall_name'
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
            });
        });
        $('table').on('click', '.sessiontheme-delete', function(e) {
            $.ajax({
                url: $(this).data('href'),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(data) {
                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    })
                    sessionthemeTable.draw();
                },
                error: function(error) {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    })
                }
            })
        });
    </script>
@endpush
