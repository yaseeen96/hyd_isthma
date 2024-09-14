@extends('layouts.app', ['ptype' => 'parent', 'purl' => request()->route()->getName(), 'ptitle' => 'Audio Processing'])
@section('content')
    <x-content-wrapper>
        <x-slot:title>
            Audio Processing
        </x-slot>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (auth()->user()->id == 1 || auth()->user()->can('Create Members'))
                        <a href="{{ route('audioProcessing.create') }}" class="btn btn-purple float-right"><i
                                class="fas fa-plus mr-2"></i>Create</a>
                    @endif
                </div>
            </div>
            <x-table id="translations-table">
                <th>SL.No </th>
                <th>Youtube URL</th>
                <th>Language 1</th>
                <th>Language 2</th>
                <th>Action</th>
            </x-table>
        </div>
    </x-content-wrapper>
@endsection
@push('scripts')
    <script type="text/javascript">
        function clearFilters() {}
        $(function() {
            translationTable = $('#translations-table').DataTable({
                ajax: {
                    url: "{{ route('audioProcessing.index') }}",
                    // data: function(d) {}
                },
                columns: [
                    dtIndexCol(),
                    {
                        data: 'youtube_url',
                    },
                    {
                        data: 'language_1',
                    },
                    {
                        data: 'language_2',
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
            });
        });

        function setFilter() {
            translationTable.draw();
        }
    </script>
@endpush
