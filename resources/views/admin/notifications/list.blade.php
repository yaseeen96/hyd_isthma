@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Notifications'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Notifications
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="col-lg-12">
                        <a href="{{ route('notifications.create') }}" class="btn btn-purple float-right"><i
                                class="fas fa-plus mr-2"></i>Create</a>
                    </div>
                </div>
            </div>
            <x-table id="notifications-table">
                <th>SL.No </th>
                <th>Notification Criteria</th>
                <th>Title</th>
                <th>Message</th>
                <th>Image</th>
                <th>Document</th>
                <th>Youtube Url</th>
                <th>Actions</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        const notificationsTable = $("#notifications-table").DataTable({
            ajax: {
                url: "{{ route('notifications.index') }}",
            },
            columns: [
                dtIndexCol(),
                {
                    data: 'title',
                },
                {
                    data: 'notificaiton_criteria'
                },
                {
                    data: 'message'
                },
                {
                    data: 'image',
                },
                {
                    data: 'document',
                },
                {
                    data: 'youtube_url'
                },
                {
                    data: 'action'
                }
            ]
        })

        $('table').on('click', '.notification-delete', function(e) {
            $.ajax({
                url: $(this).data('href'),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 200) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Successfully.'
                        })
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Encountered an error while deleting the notificaitons.'
                        })
                    }
                    notificationsTable.draw();
                }
            })
        });
    </script>
@endpush
