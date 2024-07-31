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
                <th>Title</th>
                <th>Message</th>
                <th>Image</th>
                <th>Youtube Url</th>
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
                    name: 'title',
                    data: 'title',
                },
                {
                    name: 'message',
                    data: 'message'
                },
                {
                    name: 'image',
                    data: 'image',
                },
                {
                    name: 'youtube_url',
                    data: 'youtube_url'
                }
            ]
        })
    </script>
@endpush
